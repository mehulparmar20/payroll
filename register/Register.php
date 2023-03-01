<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once 'dbconfig.php';
require_once 'admin/api/Payroll.php';
// require_once 'admin/api/index.php';
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
            if($res['errorCode'] != 'success'){
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
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $event = new Util();
            $admin_id = $row['admin_id'];
            $admin_name = $row['admin_name'];
            $res = $this->checkPlanvalidity($admin_id, $mysqli, $event);
            if ($res) {
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
            $res['errorCode'] = "invalid";
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
        $res = $this->checkPlanvalidity($adminId, $mysqli, $event);
        $res =  array();
        if ($res) {
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
        echo json_encode($res);
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
        // $ip = $_SERVER['REMOTE_ADDR'];
        // $access_key = '850c3e72e4e6ca731ad78e02f50d45dd';
        // // Initialize CURL:
        // $ch = curl_init('http://api.ipstack.com/' . $ip . '?access_key=' . $access_key . '');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // // Store the data:
        // $json = curl_exec($ch);
        // curl_close($ch);
        // // Decode JSON response:
        // $api_result = json_decode($json, true);
        // $address = $api_result['city'] . "," . $api_result['region_code'] . "," . $api_result['country_code'];
        // $locip = $api_result['ip'];
        // $loczip = $api_result['zip'];
        // $os = $this->getOSName();
        // $browser = $this->getBrowser();
        return array("ip" => "",//$locip,
            "country" => "",//$api_result['country_code'],
            "city" => "",//$address,
            "os" => "Windows",//$os,
            "browser" => "Chrome",//$browser,
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

class Util
{
    private $eventArray = array();

    public function addEmployee($devIndex, $obj, $username, $password)
    {
        if ($this->checkDeviceStatus($devIndex, $username, $password) == "online") {
            $url = hostname . "ISAPI/AccessControl/UserInfo/Record?format=json&devIndex=" . $devIndex;
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_VERBOSE => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,    // for https
                CURLOPT_USERPWD => $username . ":" . $password,
                CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $obj
            );
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $raw_response = curl_exec($ch);
            curl_close($ch);
            return json_encode($raw_response);
        } else {
            $res = array();
            $res['deviceStatus'] = "offline";
            $res['message'] = "Device is currently unreacheable";
            $res['statusCode'] = 0;
            $res['data'] = "no";
            $res['events'] = null;
            return json_encode($res);
        }
    }

    public function rand_string( $length ) {
        $str = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

    public function preventApicall($mysqli) {
        $admin_id = $_SESSION['admin_id'];
        $sql = "select apistatus from company_admin where admin_id = " . $admin_id;
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        if ($row['apistatus'] == 0) {
            $sql = "UPDATE `company_admin` SET `apistatus` = '1' WHERE `company_admin`.`admin_id` = '$admin_id' ";
            if($mysqli->query($sql)){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    public function startApicall($mysqli){
        $admin_id = $_SESSION['admin_id'];
        $sql = "UPDATE `company_admin` SET `apistatus` = '0' WHERE `company_admin`.`admin_id` = '$admin_id' ";
        if($mysqli->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function getUserDetails($userId, $mysqli){
        $user = $mysqli->query("SELECT * FROM `add_users` WHERE `user_id` = {$userId}");
        $row = $user->fetch_assoc();
        return array('count' => $user->num_rows,'data' => $row);
    }

    public function fetchEvents($devIndex, $conn, $username, $password)
    {
        $res = array();
        if ($this->checkDeviceStatus($devIndex, $username, $password) == "offline") {
            $res['deviceStatus'] = "offline";
            $res['message'] = "Device is currently unreacheable";
            $res['statusCode'] = 0;
            $res['data'] = "no";
            $res['events'] = null;
            return json_encode($res);
        } else {
            $time = $this->getCurrentTime($devIndex, $conn);
            $dataResponse = $this->getModeEvents($devIndex, $time, $conn, $username, $password);
            // echo json_encode(array($devIndex, $time, $conn, $username, $password));
            // echo $devIndex.$time.$conn.$username.$password;
            usort($dataResponse, function ($item1, $item2) {
                return (int)$item1['serialNo'] <=> (int)$item2['serialNo'];
            });
            $res['events'] = $dataResponse;
            return $res;
        }
    }

    public function getModeEvents($devIndex, $time, $conn, $username, $password)
    {
        $res = array();
        $events = array(75);
        $len = sizeof($events);
        for ($k = 0; $k < $len; $k++) {
            $minor = $events[$k];
            $eventList = array();
            if ($time != "false") {
                $result = $this->makeApiCall($devIndex, $time, $username, $password, $minor);
                $arr = json_decode($result, true);
                $totalMatch = $arr['AcsEventSearchResult']['totalMatches'];
                if($totalMatch != 0){
                    if($totalMatch <= 30){
                        $eventList = $arr['AcsEventSearchResult']['MatchList'];
                    }else{
                        $eventList = $arr['AcsEventSearchResult']['MatchList'];
                        $time = $arr['AcsEventSearchResult']['MatchList'][sizeof($arr['AcsEventSearchResult']['MatchList']) - 1]['time'];
                        $size = 0;
                        if ($totalMatch % 30 == 0) {
                            $size = (int)$totalMatch / 30;
                        } else {
                            $size = (int)$totalMatch / 30 + 1;
                        }
                        for ($i = 1; $i <= $size; $i++) {
                            $result = $this->makeApiCall($devIndex, $time, $username, $password, $minor);
                            $arr = json_decode($result, true);
                            $eventList = array_merge($eventList, $arr['AcsEventSearchResult']['MatchList']);
                            $time = $arr['AcsEventSearchResult']['MatchList'][sizeof($arr['AcsEventSearchResult']['MatchList']) - 1]['time'];
                        }
                    }
                }
            }
            $this->eventArray = array_merge($this->eventArray, $eventList);
        }
        return $this->eventArray;
    }

    public function makeApiCall($devIndex, $time, $username, $password, $minor)
    {
        $url = hostname . "ISAPI/AccessControl/AcsEvent?format=json&devIndex=" . $devIndex;
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_VERBOSE => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{
                                                    "AcsEventSearchDescription": {
                                                        "searchID": "0",
                                                        "searchResultPosition": 0,
                                                        "maxResults": 30,
                                                        "AcsEventFilter": {
                                                            "major":5,
                                                            "minor":' . (int)$minor . ',
                                                            "startTime": "'.$time.'"
                                                        }
                                                    }    
                                                }'
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $raw_response = curl_exec($ch);
        curl_close($ch);
        return $raw_response;
    }

    public function getCurrentTime($devIndex, $conn)
    {
        $admin_id = $_SESSION['admin_id'];
        $sql = "select curr_time from company_admin where admin_id = " . $admin_id;
        if ($res = $conn->query($sql)) {
            $row = $res->fetch_assoc();
            return $row['curr_time'];
        } else {
            return "false";
        }
    }

    public function decTotalrem($conn, $count, $id)
    {
        $sql = "update `company_admin` set `remainingemployee` = '$count' where `admin_id` = '$id'";
        if($conn->query($sql)){
            return true;
        }else{
            return false;
        }

    }

    public function changeCurrentTime($devIndex, $conn, $time)
    {
        if($time != ''){
            date_default_timezone_set('Asia/Kolkata');
            $date = strtotime(substr($time,0,19)) + 1;
            $time = date("c",$date);
            $admin_id = $_SESSION['admin_id'];
            $sql = "update company_admin set curr_time = '" . $time . "' where admin_id = " . $admin_id;
            if ($conn->query($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 15; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public function checkDeviceStatus($devIndex, $username, $password)
    {
        if($devIndex != ''){
            $url = hostname . "ISAPI/System/deviceInfo?format=json&devIndex=" . $devIndex;
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_VERBOSE => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,    // for https
                CURLOPT_USERPWD => $username . ":" . $password,
                CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
                CURLOPT_POST => false,
            );

            $ch = curl_init();

            curl_setopt_array($ch, $options);
            $raw_response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($raw_response, true);
            if (array_key_exists("DeviceInfo", $response)) {
                return "online";
            } else {
                return "offline";
            }
        }else{
            return "offline";
        }
    }

    public function getShifTtime($conn, $shift_no)
    {
        $query = $conn->query("select *  from company_time where time_id = '$shift_no' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    public function getcompanytime($conn, $adminId){
        $query = $conn->query("select *  from company_time where admin_id = '$adminId' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    public function getCompanyDetails($conn, $admin_id)
    {
        $query = $conn->query("select *  from company_admin where admin_id = '$admin_id' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    // public function getUserDetails($conn, $id)
    // {
    //     $query = $conn->query("select *  from `add_users` where user_id = '$id' ");
    //     $ron = $query->fetch_assoc();
    //     return $ron;
    // }

    public function getEmpDetails($conn, $id)
    {
        $query = $conn->query("select *  from `employee` where e_id = '$id' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    public function getAttendanceDetails($conn, $emp_id, $at_date)
    {
        $query = $conn->query("select * from attendance where employee_cardno = '$emp_id' and in_time > '$at_date' ");
        $count = $query->num_rows;
        return $count;
    }

    public function getEmployee($emp_id, $conn)
    {
        // Fetch Employee Details
        $result = $conn->query("SELECT * FROM employee where emp_cardid = '$emp_id' ");
        $no = $result->num_rows;
        if ($no > 0) {
            $row = $result->fetch_assoc();
            $res = array(
                'data' => $row,
                'count' => $no,
            );
            return $res;
        } else {
            $res = array(
                'count' => $no,
            );
            return $res;
        }
    }

    public function getBreakoff($conn, $e_id)
    {
        $query = $conn->query("SELECT * FROM break_off_user where e_id = '$e_id' ");
        $count = $query->num_rows;
        return $count;
    }

    public function attendance($conn, $data)
    {
        $sql1 = "INSERT INTO `attendance` (`employee_id`, `emp_name`, `admin_id`, `employee_cardno`,`in_time`,`present_status`,`fine`,`late_time`,`devicetype`) VALUES ('" . $data['e_id'] . "', '" . $data['name'] . "', '" . $data['admin_id'] . "', '" . $data['emp_id'] . "', '" . $data['time'] . "','" . $data['present_status'] . "','" . $data['fine'] . "','" . $data['late'] . "','" . $data['devicetype'] . "')";
        if ($conn->query($sql1)) {
            return true;
        } else {
            return false;
        }
    }

    public function refreshPagedata($conn, $devIndex)
    {
        $username = $_SESSION['device_username'];
        $password = $_SESSION['device_password'];

        if($this->checkDeviceStatus($devIndex,$username,$password) == "online") {
            if($this->preventApicall($conn)){
                $data = $this->fetchEvents($devIndex, $conn, $username, $password);
                $obj = $data['events'];
                $res = $this->takeDecision($obj, $conn, $devIndex);
                $this->startApicall($conn);
                return $res;
            }
        }
    }

    public function addbreak($conn, $data)
    {
        $sql = "INSERT INTO `break`(`employee_id`,`devicetype`,`employee_cardno`, `break_time`, `admin_id`, `emp_name`) VALUES ('" . $data['e_id'] . "','" . $data['devicetype'] . "','" . $data['emp_id'] . "', '" . $data['start_time'] . "', '" . $data['admin_id'] . "', '" . $data['name'] . "')";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function endbreak($conn, $data)
    {
        $sql = "update `break` set  `violation` = '" . $data['violation'] . "',`devicetype` = '" . $data['devicetype'] . "', `fine` = '" . $data['fine'] . "', `out_time` = '" . $data['time'] . "' where b_id = '" . $data['break_id'] . "' ";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getlastBreak($conn, $data)
    {
        $empid = $data['emp_id'];
        $date = $data['at_date'];
        $check = $conn->query("SELECT * FROM break where employee_cardno = '$empid' and break_time > '$date' ORDER BY b_id DESC LIMIT 1 ");
        $row = $check->fetch_assoc();
        if ($check->num_rows > 0 && $row['out_time'] == 'OUT') {
            $val = 1;
        } else {
            $val = 0;
        }
        $arr = array(
            'count' => $val,
            'data' => $row,
        );
        return $arr;
    }

    public function deleteUser($devIndex, $empId, $username, $password, $conn,$resigndate){
        $url = hostname."/ISAPI/AccessControl/UserInfoDetail/Delete?format=json&devIndex=".$devIndex;
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_VERBOSE        => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD        => $username . ":" . $password,
            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS     => '{
                                        "UserInfoDetail": {
                                            "mode": "byEmployeeNo",
                                            "EmployeeNoList": [
                                                {
                                                    "employeeNo": "'.$empId.'"
                                                }
                                            ]
                                        }
                                    }'
        );

        $ch = curl_init();

        curl_setopt_array($ch, $options);
        $raw_response  = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($raw_response,true);
        if($response['statusCode'] == 1){
            if($this->deactivateEmployee($empId,$resigndate, $conn)){
                return "success";
            }else{
                return "Error while deleting employee";
            }

        }else{
            return "failed";
        }
    }

    public function deleteEmployee($empid, $conn)
    {
        $sql = "UPDATE employee SET delete_status = '1' WHERE `employee`.`employee_cardno` = '$empid' ";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function gettotalBreak($conn, $data)
    {
        $months = date("m", $data['time']);
        $years = date('Y', $data['time']);
        $monthName = date("F", mktime(0, 0, 0, $months));

        $fromdt = strtotime("First Day Of  $monthName $years");
        $todt = strtotime("Last Day of $monthName $years");

        $sql = "SELECT * FROM `break` WHERE employee_id = " . $data['e_id'] . " and break_time BETWEEN $fromdt and $todt ";
        $result = $conn->query($sql);
        $breakdiff = 0;
        while ($row = $result->fetch_assoc()) {
            $break_in = $row['break_time'];
            $break_out = $row['out_time'];
            if ($break_out == 'OUT') {
                break;
            }
            $diff_time = $break_out - $break_in;
            $breakdiff += $diff_time;
        }
        return $breakdiff;
    }

    public function takeDecision($obj, $conn, $devIndex)
    {

        $size = sizeof($obj);
        $lasttime = '';
        $att = array();
        for ($i = 0; $i < $size; $i++) {
            if(!in_array($obj[$i]['time'], $att)){
                $att[] = $obj[$i]['time'];
            }else{
                continue;
            }
            $timezone = '';
            $shift_no = '';
            $ch_date = '';
            $at_date = '';
            $currenttime = '';
            $lasttime = '';
            $date = '';
            $fine = 0;
            $diff = 0;
            $emp_id = $obj[$i]['employeeNoString'];
            // get data from employee Table
            $empdata = $this->getEmployee($emp_id, $conn);
            // if Employee is not exsiting in the system skip the all methods
            if ($empdata['count'] == 0) {
                continue;
            }
            // else Emplyee found in the system the create all the variable
            $res = $empdata['data'];
            $first = $res['e_firstname'];
            $last = $res['e_lastname'];
            $e_id = $res['e_id'];
            $admin_id = $res['admin_id'];
            $shift_no = $res['shift_no'];
            //  combine the Fristname and lastname
            $name = $first . " " . $last;
            // get Shift Details
            $time = $this->getShifTtime($conn, $shift_no);
            $break_time = $time['company_break_time'];
            $company_intime = $time['company_in_time'];
            $company_outtime = $time['company_out_time'];
            $timezone = $time['timezone'];
            $breakfine = $time['break_fine'];
            $latefine = $time['late_fine'];
            date_default_timezone_set($timezone);
            $maxbreak = 30;
            // Store the comapny start Time in variable
            $at_date = strtotime(date("Y-m-d 00:00:00"));
            $ch_date = strtotime(date("Y-m-d $company_intime:00"));
            // Get company Details
            $companyDetails = $this->getCompanyDetails($conn, $admin_id);
            $c_email = $companyDetails['admin_email'];
            // convert Punch date and time to timestamp
            $lasttime = $obj[$i]['time'];
            $filterdate = str_replace("T"," ",substr($lasttime, 0, 19));
            $cdate = $this->changeTimeZone($filterdate,'Asia/Kolkata',$timezone);
            $date = strtotime($cdate);
            //Device Type
            $devicetype = $obj[$i]['minor'];
            $currenttime = $date;
            if ($this->getAttendanceDetails($conn, $emp_id, $at_date) == 0) {
                $present_status = "On Time";
                $late = 0;
                $fine = 0;
                $vipArry = array(); 
                // $vipArry = array(13,14,16,17,18,47,54,105); 
                // if Entry Not Found and employee in_time  is greter then company time then sent mail to Admin With Details
                if ($currenttime > $ch_date) {
                    if(!in_array($e_id, $vipArry)){
                        $diff = $currenttime - $ch_date;
                        $late = ceil($diff / 60);
                        $fine = round($late * $latefine);
                        $present_status = "Late";
                    }
                }
                $data = array(
                    'fine' => $fine,
                    'late' => $late,
                    'e_id' => $e_id,
                    'admin_id' => $admin_id,
                    'emp_id' => $emp_id,
                    'time' => $date,
                    'present_status' => $present_status,
                    'name' => $name,
                    'devicetype' => $devicetype,
                );
                if ($this->attendance($conn, $data)) {

                } else {
                    break;
                }
            } else {
                // Check user in Break off list
                $breakoff = $this->getBreakoff($conn, $e_id);
                $data = array(
                    'emp_id' => $emp_id,
                    'at_date' => $at_date
                );
                if($breakoff == 0) {
                    $break_data = $this->getlastBreak($conn, $data);
                    $val = $break_data['count'];
                    // get details last break
                    $diff = 0;
                    if ($val > 0) {
                        // Create variable for Last break
                        $row = $break_data['data'];
                        $b_time = $row["break_time"];
                        $out_time = $row["out_time"];
                        $last_id = $row['b_id'];
                        $violation = 'No';
                        $final = 0;
                        // if user is OUT then
                        if ($out_time == "OUT") {
                            // $diff is diffreance between $time and $b_time
                            $diff = $currenttime - $b_time;
                            $total_time = ceil($diff / 60);
                        }
                        $maxbreakSeconds = $maxbreak*60;
                        if ($diff > $maxbreakSeconds) {
                            $final = $total_time - $maxbreak;
                            $t_break = $final;
                            $final *= $breakfine;
                            $violation = "Yes";
                        }
                        $data = array(
                            'e_id' => $e_id,
                            'fine' => round($final),
                            'violation' => $violation,
                            'time' => $date,
                            'break_id' => $last_id,
                            'devicetype' => $devicetype,
                        );
                        if ($this->endbreak($conn, $data)) {

                        } else {
                            break;
                        }
                    } else {
                        $data = array(
                            'e_id' => $e_id,
                            'name' => $name,
                            'admin_id' => $admin_id,
                            'emp_id' => $emp_id,
                            'start_time' => $date,
                            'devicetype' => $devicetype,
                        );
                        if ($this->addbreak($conn, $data)) {

                        } else {
                            break;
                        }
                    }
                }
            }
        }
        if($this->changeCurrentTime($devIndex, $conn, $lasttime)){
            return true;
        }else{
            return false;
        }
    }


    public function changeTimeZone($dateString, $timeZoneSource = null, $timeZoneTarget = null)
    {
        if (empty($timeZoneSource)) {
            $timeZoneSource = date_default_timezone_get();
        }
        if (empty($timeZoneTarget)) {
            $timeZoneTarget = date_default_timezone_get();
        }

        $dt = new DateTime($dateString, new DateTimeZone($timeZoneSource));
        $dt->setTimezone(new DateTimeZone($timeZoneTarget));

        return $dt->format("Y-m-d H:i:s");
    }

    public function deactivateEmployee($empid,$resigndate, $conn)
    {
        $sql = "UPDATE `employee` SET `employee_status`= 0 ,`resign_date` = '$resigndate' WHERE emp_cardid = '$empid' ";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false.$conn->error;
        }
    }
    
    public function getnewEmployeecount($adminId, $conn) {
        $start = strtotime(date("Y-m-01 00:00:00"));
        $months = date('m');
        $years= date('Y');
        $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
        $end = strtotime(date("Y-m-".$days." 23:59:59"));
        $sql = "SELECT COUNT(e_id) FROM `employee` WHERE admin_id = '$adminId' and employee_status = 1 and employee.join_date BETWEEN '$start' and '$end' ";
        if ($res = $conn->query($sql)) {
            $row = $res->fetch_assoc();
            return $row['COUNT(e_id)'];
        } else {
            return 0;
        }
    }

    public function getleftEmployeecount($adminId, $conn) {
        $start = strtotime(date("Y-m-01 00:00:00"));
        $months = date('m');
        $years= date('Y');
        $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
        $end = strtotime(date("Y-m-".$days." 23:59:59"));
        $sql = "SELECT COUNT(e_id) FROM `employee` WHERE admin_id = '$adminId' and employee_status = 0 and employee.resign_date BETWEEN '$start' and '$end' ";
        if ($res = $conn->query($sql)) {
            $row = $res->fetch_assoc();
            return $row['COUNT(e_id)'];
        } else {
            return 0;
        }
    }

    public function deccounter($adminId, $conn)
    {
        $sql = "UPDATE `company_admin` SET `totalemployee`= `totalemployee` - 1 WHERE admin_id = '$adminId'";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false.$conn->error;
        }
    }
    
    public function incrementtotal($adminId, $quntity,$conn) {
        $sql = "UPDATE `company_admin` SET `remainingemployee`= `remainingemployee` + ".$quntity.", `totalemployee`= `totalemployee` + ".$quntity." WHERE admin_id = '$adminId'";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function emailExists($email, $conn){
        $company = "SELECT * FROM `company_admin` WHERE `admin_email` = '$email' ";
        $user = "SELECT * FROM `add_users` WHERE `user_email` = '$email' AND `user_status` = 0 ";
        $emaployee = "SELECT * FROM `employee` WHERE `e_email` = '$email' AND `employee_status` = 1 ";
        $comres = $conn->query($company);
        if($comres->num_rows == 0){
            $userres = $conn->query($user);
            if($userres->num_rows == 0){
                $empres = $conn->query($emaployee);
                if($empres->num_rows == 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function activateEmployee($devIndex, $username, $password, $empId, $conn)
    {
        $empdata = $this->getEmployee($empId,$conn);
        $row = $empdata['data'];
        $name = $row['e_firstname']." ".$row['e_lastname'];
        $startdate = substr(date('c', $row['join_date']), 0, 19);
        $user = '{
                    "UserInfo" : [{
                    "employeeNo": "'.$empId.'",
                    "name": "'.$name.'",
                    "userType": "normal",
                    "Valid" : {
                    "enable": true,
                    "beginTime": "'.$startdate.'",
                    "endTime": "2035-08-01T17:30:08",
                    "timeType": "local"
                    }
                    }]
                }';
        $res = $this->addEmployee($devIndex, $user, $username, $password);
        $res = json_decode($res,true);
        $res = json_decode($res,true);
        $data = $res['UserInfoOutList']['UserInfoOut'];
        $status = $data[0]['statusCode'];
        if($status == 1) {
            $sql = "UPDATE `employee` SET `employee_status`= 1 WHERE emp_cardid = '$empId' ";
            if ($conn->query($sql)) {
                return true;
            } else {
                return false . $conn->error;
            }
        }
    }

}