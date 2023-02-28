<?php
// exit();
ob_start();
include '../dbconfig.php';
// Month
$months = 05;
// Year
$years= 2021;
// Admin Id
$admin_id= 1;
$days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
// Month First Day
$f_date = strtotime(date("1-$months-$years 00:00:00"));
// Month Last Day
$t_date = strtotime(date("$days-$months-$years 23:59:59"));
// Check salary is already Added or not
$q = mysqli_query($conn, "select * from staff_salary where salary_status = 1 and admin_id = '$admin_id' and staff_salary_date between '$f_date' and '$t_date'") ;
$no = 0;
while ($row = mysqli_fetch_array($q))
{
    $no++;
}
// Salary is not added then goto inside condition
// if($no == 0)
// {
    // fetch total days of month
    $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
    // create class
    class employee
    {
        // define global veriable
        private $name;
        private $date = array();
        private $name1;
        private $break_violation;
        private $emp_cardno;
        private $salary;
        private $id;
        private $late_fine;
        private $benifits;
        // create constructer
        function __construct($name)
        {
            // define month and years
            $months = 02;
            $years= 2021;
            $days = cal_days_in_month(CAL_GREGORIAN,$months,$years);
            $this->name = $name;
            // store initial 0 value in all date
            for($i = 0; $i <= $days; $i++){
                $this->date[] = 0;
            }
        }
        // create Getter and setters

        function getSalary() {
            return $this->salary;
        }
        function getId() {
            return $this->id;
        }
        function getBreak_violation() {
            return $this->break_violation;
        }

        function setBreak_violation($break_violation) {
            $this->break_violation += $break_violation;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setSalary($salary) {
            $this->salary = $salary;
        }
        function getEmp_cardno() {
            return $this->emp_cardno;
        }

        function setEmp_cardno($emp_cardno) {
            $this->emp_cardno = $emp_cardno;
        }

        function getName() {
            return $this->name;
        }

        function getName1() {
            return $this->name1;
        }

        public function getBenifits()
        {
            return $this->benifits;
        }

        public function setBenifits($benifits)
        {
            $this->benifits = $benifits;
        }

        function getDate($i) {
            return $this->date[$i];
        }

        function getLateFine()
        {
            return $this->late_fine;
        }

        function setLateFine($late_fine)
        {
            $this->late_fine += (float)$late_fine;
        }

        function setName($name) {
            $this->name = $name;
        }

        function setName1($name1) {
            $this->name = $name1;
        }

        function setDate($date, $i) {
            $this->date[$i] = $date;
        }

    }
    
    $k = 0;
    //$n = 0;
    $emp = array();
    $dataarr = array();
    // Fetch the company time
    $query = mysqli_query($conn, "select *  from company_time where admin_id = '1' ");
    while($ron = mysqli_fetch_array($query))
    {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
        $timeid = $ron['time_id'];
    // fetch employee deails
    // $result1 = mysqli_query($conn, "SELECT * FROM employee where admin_id = '$admin_id' and employee_status = 1 and delete_status = 0");
    $result1 = mysqli_query($conn, "SELECT * FROM employee  where admin_id = '1' and shift_no = '$timeid'  and employee_status = 1 and delete_status = 0");


    while($row = mysqli_fetch_array($result1))
    {
        $employee[$row['e_id']] = $k;
        $id = $row['e_id'];
        $name = $row['e_firstname']." ".$row['e_lastname'];
        $salary = $row['e_salary'];
        $emp_card_id = $row['emp_cardid'];
        $shift_no = $row['shift_no'];
        $benifits = $row['e_benefits'];
        array_push($emp, new employee($employee[$row['e_id']]));
        $emp[$employee[$id]]->setName1($name);
        $emp[$employee[$id]]->setsalary($salary);
        $emp[$employee[$id]]->setId($id);
        $emp[$employee[$id]]->setBenifits($benifits);
        $emp[$employee[$id]]->setEmp_cardno($emp_card_id);
        $k++;
    }
     date_default_timezone_set($timezone);
    $monthName = date("F", mktime(0, 0, 0, $months));
    $sal_date = strtotime(date("3-$months-$years 00:00:00"));
    $fromdt= strtotime("First Day Of  $monthName $years ");
    $todt= strtotime("Last Day of $monthName $years 23:59:00");
    //fetch the Monthly attendance
    // $result = mysqli_query($conn, "SELECT * FROM attendance where admin_id = '$admin_id' and in_time between $fromdt and $todt  ");
    $result = mysqli_query($conn, "SELECT * FROM attendance INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = '1' AND employee.shift_no = '$timeid' and attendance.in_time between $fromdt and $todt  ");


    $i = 0;
    $temp = 1;
    $half = 2;
    $absent = 0;
    while($row = mysqli_fetch_array($result)){
        $id = $row['employee_id'];
        $name = $row['emp_name'];
        $time = $row['in_time'];
        $fine = $row['fine'];
        $date = date("j", $time);
        if(array_key_exists($id,$employee)){
            // if full day count full day
            if($row['attendance_status'] == "Full"){
                $emp[$employee[$id]]->setDate($temp,$date);
            }
            // elseif count Half Day
            elseif ($row['attendance_status'] == "Half"){
                $emp[$employee[$id]]->setDate($half,$date);
            }
            //  else count Absent
            else{
                //               $emp[$employee[$id]]->setDate($absent,$date);
            }
            $emp[$employee[$id]]->setName1($name);
            $emp[$employee[$id]]->setLateFine($fine);
        }
    }
    // calculate total violation fine of break
    $break_query = mysqli_query($conn, "SELECT * FROM break where admin_id = '$admin_id' and break_time between $fromdt and $todt  ");

    while($row = mysqli_fetch_array($break_query)){
        $id = $row['employee_id'];
        $fine = $row['fine'];
        if($fine == '')
        {
            $fine = 0;
        }
        if(array_key_exists($id,$employee)) {
            $emp[$employee[$id]]->setBreak_violation($fine);
        }
    }
    }
    // fetch total working days
    $working_days = mysqli_query($conn, "Select * from working_days where admin_id = '$admin_id' ");
    $info = mysqli_fetch_array($working_days);
    $day1 = $info['mon'];
    $day2 = $info['tue'];
    $day3 = $info['wed'];
    $day4 = $info['thu'];
    $day5 = $info['fri'];
    $day6 = $info['sat'];
    $day7 = $info['sun'];
    $working_days = $day1 + $day2 + $day3 + $day4 + $day5 + $day6 + $day7;

    // go to inside if when working days is 5 and saturday and sunday is 0
    if($working_days == 5 && $day6 == 0 && $day7 == 0){
        $startdate = strtotime($years . '-' . $months . '-01');
        $enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
        $currentdate = $startdate;
        //get the total number of days in the month
        $return = intval((date('t',$startdate)),10);
        //loop through the dates, from the start date to the end date
        while ($currentdate <= $enddate)
        {
            //if you encounter a Saturday or Sunday, remove from the total days count
            if ((date('D',$currentdate) == 'Sat') || (date('D',$currentdate) == 'Sun'))
            {
                $return = $return - 1;
            }
            $currentdate = strtotime('+1 day', $currentdate);
        }
    }
    // going inside elseif when working days is 6
    elseif($working_days == 6){
        $startdate = strtotime($years . '-' . $months . '-01');
        $enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
        $currentdate = $startdate;
        //get the total number of days in the month
        $return = intval((date('t',$startdate)),10);
        //loop through the dates, from the start date to the end date
        while ($currentdate <= $enddate)
        {
            //if you encounter a Saturday or Sunday, remove from the total days count
            if (date('D',$currentdate) == 'Sun')
            {
                $return = $return - 1;
            }
            $currentdate = strtotime('+1 day', $currentdate);
        }
    }
    // going indse else part when total working days is 7   and here return  is indicate that total working days
    else{
        $return = $days;
    }
    // fetch any holiday is present in this month if present it cloud be minius into the total working days
    $holidays = mysqli_query($conn, "SELECT * FROM holidays where admin_id = '$admin_id' and holiday_date between $fromdt and $todt  ");
    $holiday = mysqli_num_rows($holidays);
    $return = $return - $holiday;
    // salary additional tax and pf calculation
    $salary =mysqli_query($conn, "SELECT * FROM salary_setting WHERE admin_id =  '$admin_id' ");
    $sla_cal = mysqli_fetch_array($salary);
    $da = $sla_cal['da'];
    $hra = $sla_cal['hra'];
    $conveyance = $sla_cal['conveyance'];
    $allow = $sla_cal['allow'];
    $m_allow = $sla_cal['m_allow'];
    $other = $sla_cal['others'];
    $tds = $sla_cal['tds'];
    $esi = $sla_cal['esi'];
    $pf = $sla_cal['pf'];
    $leave = $sla_cal['leave'];
    $proftax = $sla_cal['proftax'];
    $l_wel = $sla_cal['l_wel'];
    $salary_no = 0;
    for($j = 0; $j < sizeof($emp); $j++) {
        $present = 0;
        $half = 0;

        $total_salary = $emp[$j]->getSalary();
        $net_salary = $emp[$j]->getSalary();
        $emp_card_id = $emp[$j]->getEmp_cardno();
        $day_salary = round($total_salary / $return,2);

        // Earning and Deduction Calculation
        // Earning
        $earning = 0;
        if ($da == 'not used') {
            $s_da = 'not used';
        } else {
            $s_da = $total_salary * $da / 100;
            $earning = $s_da;
        }
        if ($hra == 'not used') {
            $s_hra = 'not used';
        } else {
            $s_hra = $total_salary * $hra / 100;
            $earning += $s_hra;
        }
        if ($conveyance == 'not used') {
            $s_conveyance = 'not used';
        } else {
            $s_conveyance = $total_salary * $conveyance / 100;
            $earning += $s_conveyance;
        }
        if ($allow == 'not used') {
            $s_allow = 'not used';
        } else {
            $s_allow = $total_salary * $allow / 100;
            $earning += $s_allow;
        }
        if ($m_allow == 'not used') {
            $s_m_allow = 'not used';
        } else {
            $s_m_allow = $total_salary * $m_allow / 100;
            $earning += $s_m_allow;
        }
        if ($other == 'not used') {
            $s_other = 'not used';
        } else {
            $s_other = $total_salary * $other / 100;
            $earning += $s_other;
        }
        // Deduction
        $deduction = 0;
        if ($tds = 'not used') {
            $s_tds = 'not used';
        } else{
            $s_tds = $total_salary * $tds / 100;
            $deduction = $s_tds;
        }
        if ($esi = 'not used') {
            $s_esi = 'not used';
        } else {
            $s_esi = $total_salary * $esi / 100;
            $deduction += $s_esi;
        }
        if ($pf = 'not used') {
            $s_pf = 'not used';
        } else{
            $s_pf = $total_salary * $pf / 100;
            $deduction += $s_pf;
        }
        if ($proftax = 'not used') {
            $s_proftax = 'not used';
        } else {
            $s_proftax = $total_salary * $proftax / 100;
            $deduction += $s_proftax;
        }
        if ($l_wel = 'not used') {
            $s_l_wel = 'not used';
        } else{
            $s_l_wel = $total_salary * $l_wel / 100;
            $deduction += $s_l_wel;
        }
        $name = $emp[$j]->getName();
        for($k = 1; $k <= $days; $k++)
        {
            if($emp[$j]->getDate($k) == 1)
            {
                $present++;
            }elseif($emp[$j]->getDate($k) == 2){
                $half++;
                $present++;
            }else{

            }
        }

        // if present is greter then working day the it  is calculate in overtime
        $e_id = $emp[$j]->getId();
        $benifits = $emp[$j]->getBenifits();
        // get the total remaining paid leaves of employee
        // $return is total working days in company
        $r_leave = 0;
        $paid_leave = 0;
        $over_time = 0;
        if($present > $return){
            $over_time = $present - $return;
            $leave = 0;
        }else {
            $leave =  $return - $present;
        }


        $h_leave = $half * 0.5;
        $leave = $leave + $h_leave;
        $total_leave = $leave;
        if($benifits == 1){
            $add_leave = mysqli_query($conn, "SELECT * FROM `total_add_leave` WHERE `e_id` = '$e_id' ");
            $row = mysqli_fetch_array($add_leave);
            $r_leave = $row['total_leave'];
            if($r_leave == ''){
                $r_leave = 0;
            }
            // get the benifit
            $com_ben = mysqli_query($conn, "select * from company_benefits where admin_id = '$admin_id' ");
            $row = mysqli_fetch_array($com_ben);
            $allow_leave = $row['allow_leave'];
            $r_leave += $allow_leave;

            if($leave > 0 && $leave > $r_leave){

                $leave = $leave - $r_leave;
                $temp = $total_leave - $leave;
                $r_leave = $r_leave - $temp;
                $paid_leave = $temp;
            }
            if($leave > 0 && $r_leave > $leave){
//                         $r_leave = $r_leave - $h_leave;
                $r_leave = $r_leave - $leave;
                $paid_leave = $leave;
                $leave = $r_leave - $leave;
                $leave = 0;
            }
        }
//                 echo "-------------------------";
//                 echo $name."->".$total_leave."<br>";
        // Paid Leave Salary Calculation
        $total_paid_leave = $day_salary * $paid_leave;
        //Leave Full  Decucation
        $leave_deduct = $day_salary * $leave;
        // Over Time Paay
        $over_time_pay = $over_time * $day_salary;
        // Leave Half  Decucation
        $half_dedcution = $h_leave * $day_salary;
        // Break Violation Deducation
        $fine = $emp[$j]->getBreak_violation();
        $late_fine = $emp[$j]->getLateFine();
        // Grosspay Calculation
        $grosspay = $total_salary + $earning;
        // Afetr all deduction get a final salary
        $total_salary = $total_salary  - $leave_deduct - $deduction - $fine - $late_fine + $over_time_pay;
        // Calculate all the deduction
        $deduction = $leave_deduct + $deduction;
        // add the earning salary in total salary
        $total_salary = $total_salary + $earning;
        // Add total Leave Deduction
        $paid_deduct = $total_leave * $day_salary;

        if($leave != 0){
            $present = $present - $h_leave;
        }

        $arr =  array(  'emp_name' => $name,
            'basic' => $net_salary,
            'par_day_salary' => $day_salary,
            // 'emp_cardid' => $emp_card_id,
            'working_days' => $return,
            // 'da' => $s_da,
            'present_days' => $present,
            'absent_days' => $leave,
            // 'hra' =>$s_hra,
            // 'conveyance' =>$s_conveyance,
            // 'allowance' =>$s_allow,
            // 'medical_allowance' =>$s_m_allow,
            // 'others' =>$s_other,
            // 'tds' =>$s_tds,
            // 'esi' =>$s_esi,
            // 'pf' =>$s_pf,
            'Leave' =>$leave_deduct,
            // 'paid_leave' =>$paid_leave,
            'Break Violation' =>$fine,
            'Late Violation' =>$late_fine,
            // 'prof_tax' =>$s_proftax,
            // 'labour_welfare' =>$s_l_wel,
            // 'earning' =>$earning,
            // 'deduction' =>$deduction,
            // 'Gross Pay' =>$grosspay,
            // 'Ove Time' =>$over_time_pay,
            'Net Salary' =>$total_salary,
            // 'staff_salary_date' =>$sal_date,
            // 'salary_added_date' =>$time,
            // 'e_id' =>$e_id,
            // 'admin_id'=>$admin_id
            );
            echo json_encode($arr).",";
        $dataarr[] = $arr;
        // calculate the total remaning leave afet deduction in leave
//                 // add the remaning leave in database
//                 mysqli_query($conn, "UPDATE total_add_leave SET `total_leave`= '$r_leave' WHERE e_id = '$e_id' ");
//                 $time = time();
//         // Generate salary
//                  $staff_salary = "Insert into staff_salary (emp_name,
//                                                                                    basic,
//                                                                                    par_day_salary,
//                                                                                    emp_cardid,
//                                                                                    working_days,
//                                                                                    da,
//                                                                                    present_days,
//                                                                                    absent_days,
//                                                                                    hra,
//                                                                                    conveyance,
//                                                                                    allowance,
//                                                                                    medical_allowance,
//                                                                                    others,
//                                                                                    tds,
//                                                                                    esi,
//                                                                                    pf,
//                                                                                    e_leave,
//                                                                                    paid_leave,
//                                                                                    break_violation,
//                                                                                    late_fine,
//                                                                                    prof_tax,
//                                                                                    labour_welfare,
//                                                                                    earning,
//                                                                                    grosspay,
//                                                                                    deduction,
//                                                                                    net_salary,
//                                                                                    staff_salary_date,
//                                                                                    over_time,
//                                                                                    salary_added_date,
//                                                                                    e_id,
//                                                                                    admin_id)values
//                                                                                    ('$name',
//                                                                                    '$net_salary',
//                                                                                    '$day_salary',
//                                                                                    '$emp_card_id',
//                                                                                    '$return',
//                                                                                    '$s_da',
//                                                                                    '$present',
//                                                                                    '$leave',
//                                                                                    '$s_hra',
//                                                                                    '$s_conveyance',
//                                                                                    '$s_allow',
//                                                                                    '$s_m_allow',
//                                                                                    '$s_other',
//                                                                                    '$s_tds',
//                                                                                    '$s_esi',
//                                                                                    '$s_pf',
//                                                                                    '$leave_deduct',
//                                                                                    '$paid_leave',
//                                                                                    '$fine',
//                                                                                    '$late_fine',
//                                                                                    '$s_proftax',
//                                                                                    '$s_l_wel',
//                                                                                    '$earning',
//                                                                                    '$grosspay',
//                                                                                    '$deduction',
//                                                                                    '$total_salary',
//                                                                                    '$sal_date',
//                                                                                    '$over_time_pay',
//                                                                                    '$time',
//                                                                                    '$e_id',
//                                                                                    '$admin_id')";
//                  $add = 0;
//                  if($conn->query($staff_salary)){
//                      $add++;
//                  }
    }
    
    echo json_encode($dataarr);
//        if($add >0) {
//            echo "Salary Generated Successfully.";
//        }else {
//           echo "Something wrong Salary Not Added";
//        }
// }
// else{
//     echo "You Already Generated Salary of $months $years";
// }
