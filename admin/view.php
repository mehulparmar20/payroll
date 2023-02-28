v<?php

class control {

// Add policies
    function addpolicies() {
        include '../dbconfig.php';

        $policy = mysqli_real_escape_string($conn, $_POST['policy']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $sql1 = "Insert into company_policy(policy_name,policy_description,policy_department)values('$policy','$description','$department')";
        if ($conn->query($sql1)) {
            echo "Policies added Successfully.";
        } else {
            echo "Policies Not Added";
        }
        mysqli_close($conn);
    }

    // Add salary
    function addsalary() {
        include 'dbconfig.php';
        //insert data in database
        $select_staff = mysqli_real_escape_string($conn, $_POST['select_staff']);
        $basic = mysqli_real_escape_string($conn, $_POST['basic']);
        $da = mysqli_real_escape_string($conn, $_POST['da']);
        $hra = mysqli_real_escape_string($conn, $_POST['hra']);
        $conveyance = mysqli_real_escape_string($conn, $_POST['conveyance']);
        $allow = mysqli_real_escape_string($conn, $_POST['allow']);
        $m_allow = mysqli_real_escape_string($conn, $_POST['m_allow']);
        $others = mysqli_real_escape_string($conn, $_POST['others']);
        $tds = mysqli_real_escape_string($conn, $_POST['tds']);
        $esi = mysqli_real_escape_string($conn, $_POST['esi']);
        $pf = mysqli_real_escape_string($conn, $_POST['pf']);
        $e_leave = mysqli_real_escape_string($conn, $_POST['e_leave']);
        $proftax = mysqli_real_escape_string($conn, $_POST['proftax']);
        $l_wel = mysqli_real_escape_string($conn, $_POST['l_wel']);
        $other1 = mysqli_real_escape_string($conn, $_POST['other1']);
//        $s_id = mysqli_real_escape_string($conn,$_POST['s_id']);
        //calculation For Net Salary
        //calculation For Percentage To Amount
        if (!empty($basic)) {
            if ($basic) {
                $bda = $basic * $da / 100;
            } else {
                $bda = 0;
            }
            $grosssalary = $bda;

            //HRA calculation For Percentage To Amount
            if ($basic) {
                $bhra = $basic * $hra / 100;
            } else {
                $bhra = 0;
            }
            $grosssalary += $bhra;

            //Conveyance calculation For Percentage To Amount
            if ($basic) {
                $con = $basic * $conveyance / 100;
            } else {
                $con = 0;
            }
            $grosssalary += $con;

            // allow calculation For Percentage To Amount
            if ($basic) {
                $ballow = $basic * $allow / 100;
            } else {
                $ballow = 0;
            }
            $grosssalary += $ballow;

            //mallow calculation For Percentage To Amount
            if ($basic) {
                $mallow = $basic * $m_allow / 100;
            } else {
                $mallow = 0;
            }
            $grosssalary += $mallow;

            //other calculation For Percentage To Amount
            if ($basic) {
                $bother = $basic * $others / 100;
            } else {
                $bother = 0;
            }
            $grosssalary += $bother;
        }
        $earning = $grosssalary;
        //Gross Pay
        // echo "Total Earnings is: " . $grosssalary . "<br>";
        $netsalary = $grosssalary + $basic;
        $netsalary = ceil($netsalary);
        // echo "Gross Salary is: " . $netsalary . "<br><br>";
        //Deductions TDS 
        if (!empty($netsalary)) {
            if ($netsalary) {
                @$dtds = $netsalary * $tds / 100;
            } else {
                $dtds = 0;
            }
            $tax = $dtds;

            //ESI calculation For Percentage To Amount
            if ($netsalary) {
                @$desi = $netsalary * $esi / 100;
            } else {
                $desi = 0;
            }
            $tax += $desi;

            //PF calculation For Percentage To Amount
            if ($netsalary) {
                @$dpf = $netsalary * $pf / 100;
            } else {
                $dpf = 0;
            }
            $tax += $dpf;

            //Leave calculation For Percentage To Amount
            if ($netsalary) {
                @$leave = $netsalary * $e_leave / 100;
            } else {
                $leave = 0;
            }
            $tax += $leave;

            //Prof Tax calculation For Percentage To Amount
            if ($netsalary) {
                @$dproftax = $netsalary * $proftax / 100;
            } else {
                $dproftax = 0;
            }
            $tax += $dproftax;

            //lw calculation For Percentage To Amount
            if ($netsalary) {
                @$lw = $netsalary * $l_wel / 100;
            } else {
                $lw = 0;
            }
            $tax += $lw;

            //other calculation For Percentage To Amount
            if ($netsalary) {
                @$others = $netsalary * $other1 / 100;
            } else {
                $others = 0;
            }
            $tax += $others;
        }
        $deduction = $tax;
        //Deduction
        //echo "Total Deduction  is: " . ceil($tax) . "<br>";
        $finalsalary = $netsalary - $tax;
        $finalsalary = ceil($finalsalary);
        //echo "Net Salary is: " . $finalsalary . "<br><br>";
        // echo(ceil($finalsalary));
        $date = time();


        $sql1 = "Insert into staff_salary (select_staff,basic,da,hra,conveyance,allowance,medical_allowance,others,tds,esi,pf,e_leave,prof_tax,labour_welfare,other,earning,grosspay,deduction,net_salary,staff_salary_date)values('$select_staff','$basic','$bda','$bhra','$con','$ballow','$mallow','$bother','$dtds','$desi','$dpf','$leave','$dproftax','$lw','$others','$earning','$netsalary','$deduction','$finalsalary','$date')";
        if ($conn->query($sql1)) {
            echo "Salary added Successfully.";
        } else {
            echo "Salary Not Added";
        }
        mysqli_close($conn);
    }

    // Update salary
    function update() {
        include 'dbconfig.php';
        //insert data in database
        $select_staff = mysqli_real_escape_string($conn, $_POST['select_staff']);
        $basic = mysqli_real_escape_string($conn, $_POST['basic']);
        $da = mysqli_real_escape_string($conn, $_POST['da']);
        $hra = mysqli_real_escape_string($conn, $_POST['hra']);
        $conveyance = mysqli_real_escape_string($conn, $_POST['conveyance']);
        $allow = mysqli_real_escape_string($conn, $_POST['allow']);
        $m_allow = mysqli_real_escape_string($conn, $_POST['m_allow']);
        $others = mysqli_real_escape_string($conn, $_POST['others']);
        $tds = mysqli_real_escape_string($conn, $_POST['tds']);
        $esi = mysqli_real_escape_string($conn, $_POST['esi']);
        $pf = mysqli_real_escape_string($conn, $_POST['pf']);
        $e_leave = mysqli_real_escape_string($conn, $_POST['e_leave']);
        $proftax = mysqli_real_escape_string($conn, $_POST['proftax']);
        $l_wel = mysqli_real_escape_string($conn, $_POST['l_wel']);
        $other1 = mysqli_real_escape_string($conn, $_POST['other1']);
        $s_id = mysqli_real_escape_string($conn, $_POST['s_id']);
        //calculation For Net Salary
        //calculation For Percentage To Amount
        if (!empty($basic)) {
            if ($basic) {
                $bda = $basic * $da / 100;
            } else {
                $bda = 0;
            }
            $grosssalary = $bda;

            //HRA calculation For Percentage To Amount
            if ($basic) {
                $bhra = $basic * $hra / 100;
            } else {
                $bhra = 0;
            }
            $grosssalary += $bhra;

            //Conveyance calculation For Percentage To Amount
            if ($basic) {
                $con = $basic * $conveyance / 100;
            } else {
                $con = 0;
            }
            $grosssalary += $con;

            // allow calculation For Percentage To Amount
            if ($basic) {
                $ballow = $basic * $allow / 100;
            } else {
                $ballow = 0;
            }
            $grosssalary += $ballow;

            //mallow calculation For Percentage To Amount
            if ($basic) {
                $mallow = $basic * $m_allow / 100;
            } else {
                $mallow = 0;
            }
            $grosssalary += $mallow;

            //other calculation For Percentage To Amount
            if ($basic) {
                $bother = $basic * $others / 100;
            } else {
                $bother = 0;
            }
            $grosssalary += $bother;
        }
        $earning = $grosssalary;
        //Gross Pay
        // echo "Total Earnings is: " . $grosssalary . "<br>";
        $netsalary = $grosssalary + $basic;
        $netsalary = ceil($netsalary);
        // echo "Gross Salary is: " . $netsalary . "<br><br>";
        //Deductions TDS 
        if (!empty($netsalary)) {
            if ($netsalary) {
                @$dtds = $netsalary * $tds / 100;
            } else {
                $dtds = 0;
            }
            $tax = $dtds;

            //ESI calculation For Percentage To Amount
            if ($netsalary) {
                @$desi = $netsalary * $esi / 100;
            } else {
                $desi = 0;
            }
            $tax += $desi;

            //PF calculation For Percentage To Amount
            if ($netsalary) {
                @$dpf = $netsalary * $pf / 100;
            } else {
                $dpf = 0;
            }
            $tax += $dpf;

            //Leave calculation For Percentage To Amount
            if ($netsalary) {
                @$leave = $netsalary * $e_leave / 100;
            } else {
                $leave = 0;
            }
            $tax += $leave;

            //Prof Tax calculation For Percentage To Amount
            if ($netsalary) {
                @$dproftax = $netsalary * $proftax / 100;
            } else {
                $dproftax = 0;
            }
            $tax += $dproftax;

            //lw calculation For Percentage To Amount
            if ($netsalary) {
                @$lw = $netsalary * $l_wel / 100;
            } else {
                $lw = 0;
            }
            $tax += $lw;

            //other calculation For Percentage To Amount
            if ($netsalary) {
                @$others = $netsalary * $other1 / 100;
            } else {
                $others = 0;
            }
            $tax += $others;
        }
        $deduction = $tax;
        //Deduction
        //echo "Total Deduction  is: " . ceil($tax) . "<br>";
        $finalsalary = $netsalary - $tax;
        $finalsalary = ceil($finalsalary);
        //echo "Net Salary is: " . $finalsalary . "<br><br>";
        // echo(ceil($finalsalary));
        $date = time();

        $sql1 = "UPDATE `staff_salary`
            SET `basic` = $basic,
            `da` = $bda,
            `hra` = $bhra,
            `conveyance` = $con,
            `allowance` = $ballow,
            `medical_allowance` = $mallow,
            `others` = $bother,
            `tds` = $dtds,
            `esi` = $desi,
            `pf` = $dpf,
            `e_leave` = $leave,
            `prof_tax` = $dproftax,
            `labour_welfare` = $lw,
            `other` = $others,
            `earning`= $earning,
            `grosspay`= $netsalary,
            `deduction`= $deduction,
            `net_salary`= $finalsalary,
            `staff_salary_date`= $date
            WHERE salary_id =$s_id";
        if ($conn->query($sql1)) {
            echo "Salary added Successfully.";
        } else {
            echo "Salary Not Added";
        }
        mysqli_close($conn);
    }

    // Update policies
    function pupdate() {
        include 'dbconfig.php';

        $policy = mysqli_real_escape_string($conn, $_POST['policy_name']);
        $description = mysqli_real_escape_string($conn, $_POST['policy_description']);
        $department = mysqli_real_escape_string($conn, $_POST['policy_department']);
        $p_id = mysqli_real_escape_string($conn, $_POST['p_id']);

        $sql1 = "UPDATE `company_policy`
            SET `policy_name` = '$policy',
            `policy_description` = '$description',
            `policy_department` = '$department'
            WHERE `policy_id` = $p_id";
        if ($conn->query($sql1)) {
            echo "Policies added Successfully.";
        } else {
            echo "Policies Not Added";
        }
        mysqli_close($conn);
    }

}
?>