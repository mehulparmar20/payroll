<?php
require "config.php";
if (!isset($_SESSION)) {
    session_start();
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
            $seq = $this->getCurrentSequence($devIndex, $conn);
            $dataResponse = $this->getModeEvents($devIndex, $seq, $conn, $username, $password);
            usort($dataResponse, function ($item1, $item2) {
                return (int)$item1['serialNo'] <=> (int)$item2['serialNo'];
            });
            $res['events'] = $dataResponse;
            $res['serial'] = $seq;
            return $res;
        }
    }

    public function getModeEvents($devIndex, $seq, $conn, $username, $password)
    {
        $res = array();
        $serialNo = $seq;
        $events = array(1, 38, 75);
        $curr_seq = 0;

        $len = sizeof($events);
        for ($k = 0; $k < $len; $k++) {
            $flag = true;
            $minor = $events[$k];
            if ($minor == 1) {
                $curr_seq = $seq['card'];
            } else if ($minor == 38) {
                $curr_seq = $seq['finger'];
            } else if ($minor == 75) {
                $curr_seq = $seq['face'];
            }
            $eventList = array();
            if ($seq != "-1") {
                $result = $this->makeApiCall($devIndex, $curr_seq, $username, $password, $minor);
                $arr = json_decode($result, true);
                $totalMatch = $arr['AcsEventSearchResult']['totalMatches'];
                if ($totalMatch == $curr_seq) {
                    $flag = false;
                } else if ($totalMatch <= $curr_seq + 30) {
                    $eventList = $arr['AcsEventSearchResult']['MatchList'];
                } else {
                    $eventList = $arr['AcsEventSearchResult']['MatchList'];
                    $diff = $totalMatch - ($curr_seq + 30);
                    $size = 0;
                    if ($diff % 30 == 0) {
                        $size = (int)$diff / 30;
                    } else {
                        $size = (int)$diff / 30 + 1;
                    }
                    for ($i = 1, $j = $curr_seq + 30; $i <= $size; $i++, $j += 30) {
                        $result = $this->makeApiCall($devIndex, $j, $username, $password, $minor);
                        $arr = json_decode($result, true);
                        $eventList = array_merge($eventList, $arr['AcsEventSearchResult']['MatchList']);
                    }
                }
            } else {
                $flag = false;
            }
            if ($flag) {
                $lastSerial = sizeof($eventList);

                if ($minor == 1) {
                    $serialNo['card'] += $lastSerial;
                } else if ($minor == 38) {
                    $serialNo['finger'] += $lastSerial;

                } else if ($minor == 75) {
                    $serialNo['face'] += $lastSerial;
                }
            }
            $this->eventArray = array_merge($this->eventArray, $eventList);
        }
        //$this->changeLastIndex($devIndex, $conn, $lastIndex);
        return $this->eventArray;
    }
    
     public function changeLastIndex($devIndex, $conn, $lastIndex){
        $admin_id = $_SESSION['admin_id'];
        $sql = "select lastIndex from company_admin where admin_id = ".$admin_id;
        if($res = $conn->query($sql)){
            $row =  $res->fetch_assoc();
            $max = $row['lastIndex'];
            if($max > 149990){
                $this->changeCurrentSequence($devIndex, $conn, array("card" => 0, "finger" => 0, "face" => 0));  
            }
        }
        else{
            return "-1";
        }
        $sql = "update company_admin set lastIndex = '".$lastIndex."' where admin_id = ".$admin_id;
        if($conn->query($sql)){
            return "true";
        }else{
            return "false";
        }
    }
    
    public function makeApiCall($devIndex, $seq, $username, $password, $minor)
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
                                                        "searchID": "' . $seq . '",
                                                        "searchResultPosition": ' . (int)$seq . ',
                                                        "maxResults": 30,
                                                        "AcsEventFilter": {
                                                            "major":5,
                                                            "minor":' . (int)$minor . '
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

    public function getCurrentSequence($devIndex, $conn)
    {
        $seqArray = array("card" => 0, "finger" => 0, "face" => 0);
        $admin_id = $_SESSION['admin_id'];
        $sql = "select card_seq, face_seq, finger_seq from company_admin where admin_id = " . $admin_id;
        if ($res = $conn->query($sql)) {
            $row = $res->fetch_assoc();
            $seqArray['card'] = $row['card_seq'];
            $seqArray['finger'] = $row['finger_seq'];
            $seqArray['face'] = $row['face_seq'];
            return $seqArray;
        } else {
            return "-1";
        }
    }

    public function changeCurrentSequence($devIndex, $conn, $serialNo)
    {
        $cardSeq = $serialNo['card'];
        $fingerSeq = $serialNo['finger'];
        $faceSeq = $serialNo['face'];
        $admin_id = $_SESSION['admin_id'];
        $sql = "update company_admin set card_seq = '" . $cardSeq . "', finger_seq = '" . $fingerSeq . "', face_seq = '" . $faceSeq . "' where admin_id = " . $admin_id;
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
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
    }

    public function getShifTtime($conn, $shift_no)
    {
        $query = $conn->query("select *  from company_time where time_id = '$shift_no' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    public function getCompanyDetails($conn, $admin_id)
    {
        $query = $conn->query("select *  from company_admin where admin_id = '$admin_id' ");
        $ron = $query->fetch_assoc();
        return $ron;
    }

    public function getAttendanceDetails($conn, $emp_id, $at_date)
    {
        $query = $conn->query("select * from attendance where employee_cardno = " . $emp_id . " and in_time > '$at_date' ");
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
            $data = $this->fetchEvents($devIndex, $conn, $username, $password);
            $obj = $data['events'];
            $serialSeq = $data['serial'];
            $this->takeDecision($obj, $serialSeq, $conn, $devIndex);
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
        $check = $conn->query("SELECT * FROM break where employee_cardno = " . $data['emp_id'] . " and break_time > " . $data['at_date'] . " ORDER BY b_id DESC LIMIT 1 ");
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

    public function takeDecision($obj, $serialNo, $conn, $devIndex)
    {

        $size = sizeof($obj);
        $facecount = 0;
        $cardcount = 0;
        $fingercount = 0;

        for ($i = 0; $i < $size; $i++) {
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
            $email = $res['e_email'];
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
            $filterdate = substr($obj[$i]['time'], 0, 19);
            $date = strtotime($filterdate);
            //Device Type
            $devicetype = $obj[$i]['minor'];
            $currenttime = time();
            if ($this->getAttendanceDetails($conn, $emp_id, $at_date) == 0) {
                $present_status = "On Time";
                $late = 0;
                $fine = 0;
                // if Entry Not Found and employee in_time  is greter then company time then sent mail to Admin With Details
                if ($currenttime > $ch_date) {
                    $late = ceil(($currenttime - $ch_date) / 60);
                    $fine = round($late * $latefine);
                    $present_status = "Late";
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
                    if ($devicetype == 1) {
                        $cardcount++;
                    } elseif ($devicetype == 38) {
                        $fingercount++;
                    } elseif ($devicetype == 75) {
                        $facecount++;
                    }
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
                $break_data = $this->getlastBreak($conn, $data);
                $val = $break_data['count'];
//                print_r($break_data);
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
                    if ($diff > 1800) {
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
                        if ($devicetype == 1) {
                            $cardcount++;
                        } elseif ($devicetype == 38) {
                            $fingercount++;
                        } elseif ($devicetype == 75) {
                            $facecount++;
                        }
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
                        if ($devicetype == 1) {
                            $cardcount++;
                        } elseif ($devicetype == 38) {
                            $fingercount++;
                        } elseif ($devicetype == 75) {
                            $facecount++;
                        }
                    } else {
                        break;
                    }
                }
            }
        }
        $serialNo['card'] += $cardcount;
        $serialNo['finger'] += $fingercount;
        $serialNo['face'] += $facecount;
        if($this->changeCurrentSequence($devIndex, $conn, $serialNo)){
            return true;
        }else{
            return false;
        }
    }

}