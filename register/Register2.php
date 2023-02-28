<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once 'dbconfig.php';
require_once 'admin/api/Payroll.php';
require_once 'admin/api/index.php';
require_once 'mail.php';
require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'vendor/razorpay/razorpay/Razorpay.php';
class Register{

    public function createcompanyAdmin($db,$data,$object){
        $subId = $object['subscription'];
        $cusId = $object['customer'];
        $plantype = $data['plantype'];
        $adminname = $data['adminname'];
        $adminemail = $data['adminemail'];
        $comname = $data['comName'];
        $admincontact = $data['admincontact'];
        $userpass = hash('sha1',$data['userpass']);
        $planproid = $data['planproid'];
        $quntity = $data['quntity'];
        $planpriid = $data['planpriceId'];
        $devproid = $data['devproId'];
        $time = time();
        $sql = "INSERT INTO `company_admin`(`company_name`, `admin_name`, `admin_contact`, `admin_email`, `admin_password`, `no_of_employee`, `subcriptionId`, `plan_name`, `plan_start`, `plan_end`, `customerId`, `activation_code`,`addedtime`)
                                    VALUES ('$comname','$adminname','$admincontact','$adminemail','$userpass','$quntity','$subId','$plantype','$time','','','','$cusId')";


    }

    public function createOrder($planId,$totalQuntity){
        $keyobj = new keys();
        $keys = $keyobj->getRazorKeys();
        $key_id = $keys['key'];
        $key_secret = $keys['secretKey'];
        $api = new Api($key_id, $key_secret);
        $planDetails = $this->getPlandetails($planId,$key_id,$key_secret,$api);
        $planId = $planDetails['id'];
        $planPrice = $planDetails['item']['amount'];
        $currency = $planDetails['item']['currency'];
        $order  = $api->order->create([
            'receipt'         => $planId,
            'amount'          => $planPrice * $totalQuntity, // amount in the smallest currency unit
            'currency'        => $currency,// <a href="/docs/payment-gateway/payments/international-payments/#supported-currencies" target="_blank">See the list of supported currencies</a>.)
        ]);
        $array = array(
            "id" => $order["id"],
            "entity" => $order["entity"],
            "amount" => $order["amount"],
            "amount_paid" => $order["amount_paid"],
            "amount_due" => $order["amount_due"],
            "currency" => $order["currency"],
            "receipt" => $order["receipt"],
            "created_at" => $order["created_at"],

        );
        return $array;
    }

    public function createSubscription($obj,$key_id,$key_secret,$api){
        $planId = $obj['planId']; // get plan id form the data array
        $planDetails = $this->getPlandetails($planId,$key_id,$key_secret); // fetch the plan details
        $planId = $planDetails['id']; // Plan id
        $planPrice = $planDetails['item']['amount']; // plan Price
        $currency = $planDetails['item']['currency']; // plan currency
        $startTime = time();
        $endTime = strtotime('+1 month', $startTime);
        $totalEmployee = $obj['totalEmployees']; // Total employees to calculate price
        $subscription  = $api->subscription->create(array(
                'plan_id' => $planId,
                'quantity' => $totalEmployee,
                'total_count' => 120,
                'start_at' => $startTime,
                'expire_by' => $endTime,
            )
        );
        return $subscription;
    }

    public function createCustomer($data,$pubKey,$secKey){
        $name = $data['companyName'];
        $contact = $data['companyContact'];
        $email = $data['companyEmail'];
        $time = time();
        $obj = '{
                    "name": '.$name.',
                    "contact": '.$contact.',
                    "email": '.$email.',
                    "created_at" : '.$time.'
                }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/customers');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);
        curl_setopt($ch, CURLOPT_USERPWD, $pubKey . ':' . $secKey);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function getPlandetails($planId,$key_id,$key_secret,$api){
        $url = "https://api.razorpay.com/v1/plans/" . $planId;
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_VERBOSE => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD => $key_id . ":" . $key_secret,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $raw_response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($raw_response,true);
        return $data;
    }

    public function authenticuser($data, $db)
    {
        $type = $data['type'];
        
        $email = $data['useremail'];
        $password = hash("sha1", $data['password']);
        if ($type == 'admin') {
            $res = $this->authAdmin($email, $password, $db);
            if($res['responsemessage'] != 'success'){
                return $this->authUser($email, $password, $db);
            }else{
                return $res;
            }
        } else {
            return $this->authEmployee($email, $password, $db);
        }
    }

    public function authEmployee($userid, $password, $mysqli)
    {
        $sql = "SELECT * FROM employee where e_email = '$userid' and e_password= '$password' and employee_status = 1 ";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $count = 0;
            $row = $result->fetch_assoc();
            $event = new Util();
            $admin_id = $row['admin_id'];
            $employeeRes = $this->checkPlanvalidity($admin_id, $mysqli, $event);
            $res = array();
            if ($employeeRes) {
                $data = $event->getCompanyDetails($mysqli, $admin_id);
                $this->setEmployeeSession($row, $data, $mysqli);
                $res['responsemessage'] = "success";
                $res['message'] = "Login Success.";
                $res['status'] = "200";
            } else {
                $res['responsemessage'] = "expired";
                $res['message'] = "Plan has been expired.";
                $res['status'] = "200";
            }
        } else {
            $res['responsemessage'] = "invalid";
            $res['message'] = "Invalid Credentials.";
            $res['status'] = "200";
        }
        return $res;
    }

    public function authAdmin($email, $password, $mysqli)
    {
        $sql = "SELECT * FROM company_admin where admin_email = '$email' and admin_password='$password'";
        $result = $mysqli->query($sql);
        $res = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $event = new Util();
            $admin_id = $row['admin_id'];
            $admin_name = $row['admin_name'];
            $planres = $this->checkPlanvalidity($admin_id, $mysqli, $event);
            if ($planres) {
                $obj = array(
                    'id' => $admin_id,
                    'name' => $admin_name,
                    'email' => $email
                );
                if($this->sentOTP($obj, $mysqli, 'admin')){
                    $res['responsemessage'] = "success";
                    $res['message'] = "OTP Sent.";
                    $res['status'] = "200";
                    $res['id'] = $admin_id;
                    $res['type'] = "admin";
                }else{
                    $res['responsemessage'] = "failed";
                    $res['message'] = "OTP Not Sent.";
                    $res['status'] = "500";
                }
            } else {
                $res['responsemessage'] = "expired";
                $res['message'] = "Plan Expired";
                $res['status'] = "200";;
            }
        }else{
            $res['responsemessage'] = "invalid";
            $res['message'] = "User Not Found.";
            $res['status'] = "200";
        }
        return $res;
    }

    public function authUser($email, $password, $mysqli)
    {
        $sql = "SELECT * FROM `add_users` where `user_email` = '$email' and `user_password` = '$password'";
        $result = $mysqli->query($sql);
        $event = new Util();
        $res = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $admin_id = $row['admin_id'];
            $user_id = $row['user_id'];
            $admin_name = $row['user_name'];
            $planres = $this->checkPlanvalidity($admin_id, $mysqli, $event);
            if ($planres) {
                $obj = array(
                    'id' => $user_id,
                    'name' => $admin_name,
                    'email' => $email
                );
                if($this->sentOTP($obj, $mysqli, 'user')){
                    $res['responsemessage'] = "success";
                    $res['message'] = "OTP Sent.";
                    $res['status'] = "200";
                    $res['id'] = $user_id;
                    $res['type'] = "user";
                }else{
                    $res['responsemessage'] = "failed";
                    $res['message'] = "OTP Not Sent.";
                    $res['status'] = "500";
                }
            } else {
                $res['responsemessage'] = "expired";
                $res['message'] = "Plan Expired";
                $res['status'] = "200";
            }
        }else{
            $res['responsemessage'] = "invalid";
            $res['message'] = "User Not Found.";
            $res['status'] = "200";
        }
        return $res;
    }

    public function verfyAdminOTP($otp, $adminId, $mysqli)
    {
        $event = new Util();
        $planres = $this->checkPlanvalidity($adminId, $mysqli, $event);
        $res =  array();
        if ($planres) {
            $row = $event->getCompanyDetails($mysqli, $adminId);
            $expired_time = $row['expired_time'];
            $login_time = time();
            if ($expired_time > $login_time) {
                $admin_id = $row['admin_id'];
                $admin_name = $row['admin_name'];
                $admin_otp = $row['otp'];
                if ($admin_otp == $otp) {
                    $planres = $this->checkPlanvalidity($admin_id, $mysqli, $event);
                    if($planres) {
                        if ($this->setAdminSession($row, $mysqli)) {
                            $res['responsemessage'] = "success";
                            $res['message'] = "Login Success.";
                            $res['status'] = "200";
                        } else {
                            $res['responsemessage'] = "failed";
                            $res['message'] = "Login Failed.";
                            $res['status'] = "500";
                        }
                    }else{
                        $res['responsemessage'] = "expired";
                        $res['message'] = "Plan Expired";
                        $res['status'] = "200";
                    }
                }else{
                    $res['responsemessage'] = "invalid";
                    $res['message'] = "OTP not Match.";
                    $res['status'] = "200";
                }
            }else{
                $res['responsemessage'] = "expired";
                $res['message'] = "OTP Expired.";
                $res['status'] = "200";
            }
        }else{
            $res['responsemessage'] = "expired";
            $res['message'] = "Plan Expired";
            $res['status'] = "200";
        }
        return $res;
    }

    public function verfyUserOTP($otp, $userId, $mysqli)
    {
        $event = new Util();
        $userres = $event->getUserDetails($userId, $mysqli);
        $res =  array();
        if ($userres['count'] > 0) {
            $userrow = $userres['data'];
            $admin_id = $userrow['admin_id'];
            $row = $event->getCompanyDetails($mysqli, $admin_id);
            $expired_time = $userrow['otpextime'];
            $login_time = time();
            if ($expired_time > $login_time) {
                $admin_otp = $userrow['otp'];
                if ($admin_otp == $otp) {
                    $planres = $this->checkPlanvalidity($admin_id, $mysqli, $event);
                    if($planres) {
                        if ($this->setUserSession($userrow, $row, $mysqli)) {
                            $res['responsemessage'] = "success";
                            $res['message'] = "Login Success.";
                            $res['status'] = "200";
                        } else {
                            $res['responsemessage'] = "failed";
                            $res['message'] = "Login Failed.";
                            $res['status'] = "500";
                        }
                    }else{
                        $res['responsemessage'] = "expired";
                        $res['message'] = "Plan Expired.";
                        $res['status'] = "200";
                    }
                }else{
                    $res['responsemessage'] = "invalid";
                    $res['message'] = "OTP not Match.";
                    $res['status'] = "200";
                }
            }else{
                $res['responsemessage'] = "expired";
                $res['message'] = "OTP Expired.";
                $res['status'] = "200";
            }
        }else{
            $res['responsemessage'] = "expired";
            $res['message'] = "Plan Expired";
            $res['status'] = "200";
        }
        return $res;
    }

    public function sentOTP($data, $conn, $type)
    {
        $ex_time = time() + 600;
        $otp = rand(100000, 999999);
        $id = $data['id'];
        $email = $data['email'];
        $name = $data['name'];
        if ($type == 'admin') {
            $sql = "update `company_admin` set `otp` = '$otp', `expired_time` = '$ex_time' where `admin_id` = '$id'";
        } elseif ($type == 'user') {
            $sql = "update `add_users` set `otp` = '$otp', `otpextime` = '$ex_time' where `user_id` = '$id'";
        }
        if ($conn->query($sql)) {
            $this->sendemail($email, $name, $otp);
            $res['responsemessage'] = "success";
            $res['message'] = "Otp updated";
            $res['status'] = "200";
        }else{
            $res['responsemessage'] = "failed";
            $res['message'] = "Otp is not update";
            $res['status'] = "200";
        }
        return $res;
    }

    public function sendemail($email_id, $name, $otp)
    {

        $string_to_name = '@@name@@';
        $string_otp = '@@otp@@';
        // for replace name
        $getmail = new Mail();
        $message = $getmail->getEmailtemplate('otp_verification');
        $message = str_replace($string_to_name, $name, $message);
        $message = str_replace($string_otp, $otp, $message);
        $subject = 'Windson Payroll OTP Verification';
        return $getmail->sentApiMail($email_id, $name, $message, $subject);
    }

    public function setLastLogintime($eid, $db, $type)
    {
        $ipdata = $this->ip();
        $ip = $ipdata['ip'];
        $city = $ipdata['city'];
        $os = $ipdata['os'];
        $browser = $ipdata['browser'];
        $time = $ipdata['signtime'];
        if ($type == 'employee') {
            $sql = "UPDATE `employee` SET `ip`= '$ip',`os`= '$os',`browser`= '$browser',`city`= '$city',`lastlogin`= '$time' WHERE e_id = '$eid' ";
        } elseif ($type == 'admin') {
            $sql = "UPDATE `company_admin` SET `ip`= '$ip',`os`= '$os',`browser`= '$browser',`logincity`= '$city',`logintime`= '$time' WHERE admin_id = '$eid' ";
        } elseif ($type == 'user') {
            $sql = "UPDATE `add_users` SET `ip`= '$ip',`os`= '$os',`browser`= '$browser',`city`= '$city',`last_login`= '$time' WHERE user_id = '$eid' ";
        }
        if ($db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function setEmployeeSession($data, $row, $conn)
    {
        $e_id = $data['e_id'];
        $admin_id = $data['admin_id'];
        $name = $data['e_firstname'] . " " . $data['e_lastname'];
        $deviceId = $row['device_id'];
        $device_username = $row['device_username'];
        $device_password = $row['device_password'];
        $_SESSION['employee'] = 'yes';
        $_SESSION['username'] = $name;
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['e_id'] = $e_id;
        $_SESSION['devIndex'] = $deviceId;
        $_SESSION['device_username'] = $device_username;
        $_SESSION['device_password'] = $device_password;
        return $this->setLastLogintime($e_id, $conn, 'employee');
    }

    public function setAdminSession($row, $conn)
    {
        $admin_id = $row['admin_id'];
        $name = $row['admin_name'];
        $deviceId = $row['device_id'];
        $device_username = $row['device_username'];
        $device_password = $row['device_password'];
        $_SESSION['admin'] = 'yes';
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_name'] = $name;
        $_SESSION['devIndex'] = $deviceId;
        $_SESSION['device_username'] = $device_username;
        $_SESSION['device_password'] = $device_password;
        return $this->setLastLogintime($admin_id, $conn, 'admin');
    }

    public function setUserSession($userrow, $row, $conn)
    {
        $admin_id = $row['admin_id'];
        $name = $userrow['user_name'];
        $device_username = $row['device_username'];
        $device_password = $row['device_password'];
        $deviceId = $row['device_id'];
        $_SESSION['admin'] = 'yes';
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_name'] = $name;
        $_SESSION['devIndex'] = $deviceId;
        $_SESSION['device_username'] = $device_username;
        $_SESSION['device_password'] = $device_password;
        return $this->setLastLogintime($admin_id, $conn, 'admin');
    }

    public function checkPlanvalidity($adminId, $conn, $obj){
        $row = $obj->getCompanyDetails($conn,$adminId);
        $plan_end = $row['plan_end'];
        $time = time();
        if ($plan_end < $time) {
            return false;
        } else {
            return true;
        }
    }

    public function ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $access_key = '850c3e72e4e6ca731ad78e02f50d45dd';
        // Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/' . $ip . '?access_key=' . $access_key . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        // Decode JSON response:
        $api_result = json_decode($json, true);
        $address = $api_result['city'] . "," . $api_result['region_code'] . "," . $api_result['country_code'];
        $locip = $api_result['ip'];
        $loczip = $api_result['zip'];
        $os = $this->getOSName();
        $browser = $this->getBrowser();
        return array("ip" => $locip,
            "country" => $api_result['country_code'],
            "city" => $address,
            "os" => $os,
            "browser" => $browser,
            "signtime" => time()
        );
    }

    public function getOSName()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Bilinmeyen İşletim Sistemi";
        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    public function getBrowser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "Bilinmeyen Tarayıcı";
        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }
        return $browser;
    }
}