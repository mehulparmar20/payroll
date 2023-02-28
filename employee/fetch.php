<?php
session_start();
include '../dbconfig.php';
include '../admin/api/index.php';
    
if ($_POST['action'] == "fetch_salary") {
    $months = $_POST['month'];
    $years = $_POST['year'];
    $f_date = strtotime(date("1-$months-$years 00:00:00"));
    $t_date = strtotime(date("30-$months-$years 23:59:59"));
    $sql = mysqli_query($conn, "select * from staff_salary where e_id = '" . $_POST['e_id'] . "' AND staff_salary_date between '$f_date' and '$t_date' ");

    $output = '';

    while ($row = mysqli_fetch_array($sql)) {

        $output = '<table id="salary" class="table table-striped custom-table datatable">
                            <thead>

                                <tr>
                                    <th colspan="2">Basic Salary : <b>' . $row['basic'] . '</b></th><th colspan="2" style="text-align: right">Date : <b>' . date('d/m/Y', $row['staff_salary_date']) . '</b></th>
                                </tr>
                            </thead>    
                            <tbody>
                                <tr>
                                    <th style="width:25%;text-align: center" colspan="2"><span style="color:green">Earning </span></th>
                                    <th style="width:25%;text-align: center" colspan="2"><span class="text-danger">Deduction </span></th>
                                </tr>
                                <tr>
                                    <th style="width:25%">DA  :</th><td style="width:25%">' . $row['da'] . '</td>
                                    <th style="width:25%">TDS  :</th><td style="width:25%">' . $row['tds'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">HRA  :</th><td style="width:25%">' . $row['hra'] . '</td>
                                    <th style="width:25%">PF  :</th><td style="width:25%">' . $row['pf'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">Conveyance  :</th><td style="width:25%">' . $row['conveyance'] . '</td>
                                    <th style="width:25%">Leave  :</th><td style="width:25%">' . $row['e_leave'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">Allowance  :</th><td style="width:25%">' . $row['allowance'] . '</td>
                                    <th style="width:25%">Prof Tax  :</th><td style="width:25%">' . $row['prof_tax'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">Medical Allowance  :</th><td style="width:25%">' . $row['medical_allowance'] . '</td>
                                    <th style="width:25%">ESI  :</th><td style="width:25%">' . $row['esi'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">Over Time :</th><td style="width:25%">' . $row['other'] . '</td>
                                    <th style="width:25%">Break Violation  :</th><td style="width:25%">' . $row['break_violation'] . '</td>
                                </tr>
                                <tr>
                                    <th style="width:25%">Incentive :</th><td style="width:25%">' . $row['break_violation'] . '</td>
                                    <th style="width:25%"></th><td style="width:25%"></td>
                                </tr>
                                <tr>
                                    <th colspan="2"></th><th colspan="2">Earn Salary : ' . $row['net_salary'] . '</th>
                                </tr>
                            </tbody>
                        </table>';
    }
    echo $output;
}

// see break 

if ($_POST['action'] == 'break_time') {
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $result1 = mysqli_query($conn, "SELECT * FROM employee where e_id = " . $_POST['e_id'] . " ");
$row = mysqli_fetch_array($result1);
    $employee[$row['e_id']] = 0;
    $id = $row['e_id'];
    $shift = $row['shift_no'];
    $query = mysqli_query($conn, "select *  from company_time where time_id = '$shift' ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }
    date_default_timezone_set($timezone);
    $c_date = date("Y-m-d 00:00:00");
    $date = strtotime($c_date);
    $diff = 0;
    if ($_POST['e_id'] != '') {
        $check = mysqli_query($conn, "SELECT * FROM break where employee_id = " . $_POST['e_id'] . " and break_time > $date ");
        $diff = 0;
        while ($row = mysqli_fetch_assoc($check)) {
            $datadiff = 0;
            $break_time = $row['break_time'];
            $break_out = $row['out_time'];
            if ($break_out != 'OUT') {
                $datadiff = $break_out - $break_time ;
            }
            $diff += $datadiff;
        }
    }
    $diffBreak = ceil($diff / 60);
    // $diffBreak = $diff;
    if ($diffBreak > 100) {
        $diffBreak = 100;
    }

    $output = '<div class="container1 chart" id="break_info" data-size="200" data-value="' . $diffBreak . '" data-arrow="up"></div>';
    echo $output;
}

// Attendance Info
if ($_POST['action'] == 'att_info') {
    $result1 = mysqli_query($conn, "SELECT * FROM employee where e_id = " . $_POST['e_id'] . " ");
    $row = mysqli_fetch_array($result1);
    $shift = $row['shift_no'];
    $query = mysqli_query($conn, "select *  from company_time where time_id = '$shift' ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set($timezone);
    $day = $_POST['day'];
    $s_date = strtotime("$day 00:00:00");
    $e_date = strtotime("$day 23:59:59");

    $diff = 0;
    $check = mysqli_query($conn, "SELECT * FROM break where employee_id = " . $_POST['e_id'] . " and break_time between '$s_date' and '$e_date' ");
    $diff = 0;
    $break_info = '';
    $no = 1;
    while ($row = mysqli_fetch_assoc($check)) {
        $break_time = $row['break_time'];
        $break_out = $row['out_time'];
        if ($break_out == 'OUT') {
            break;
        }
        $diff += $break_out - $break_time;
        $break_info .= '<li>
                                <p class="mb-0">Break ' . $no++ . '</p>
                                <p class="res-activity-time" style="color:black">
                                    <i class="fa fa-arrow-circle-right"></i>
                                    ' . date("d/m/Y h:i:s A", $row['break_time']). '</p>
                                 <p class="res-activity-time" style="color:black">        
                                    <i class="fa fa-arrow-circle-left"></i>
                                    ' . date("d/m/Y h:i:s A", $row['out_time']) . '
                                </p>
                            </li>';
    }
    $total_break = '<h6>' . ceil($diff / 60) . ' Min</h6>';
    $check = mysqli_query($conn, "SELECT * FROM attendance where employee_id = " . $_POST['e_id'] . " and in_time between '$s_date' and '$e_date' ");
    $ron = mysqli_fetch_array($check);
    $no_check = mysqli_num_rows($check);
    if ($no_check == 1) {
        $attendance = '<b><label>Punch In at : ' . date("D jS M Y h:i:s A", $ron['in_time']) . '</label></b>';
    } else {
        $attendance = 'Not Punch yet';
    }
    if ($no == 1) {
        $break_info = '<b><label>Not Taken Any Break</label></b>';
    }
    $arr = array(
        "break_time" => $break_info,
        "total_break" => $total_break,
        "attendance" => $attendance
    );

    echo json_encode($arr);
}

if ($_POST['action'] == 'salary_auth') {
    $password = hash("sha1", $_POST['password']);
    $sql = mysqli_query($conn, "SELECT * FROM employee  where e_id = " . $_POST['e_id'] . " ");
    $row = mysqli_fetch_array($sql);
    $no = mysqli_num_rows($sql);
    if ($no > 0) {
        
        $pass = $row['e_password'];
        if ($pass == $password) {
            $_SESSION['salary'] = 'yes';
            echo 'true';
        } else {
            echo 'false';
        }
    } else {
        echo 'false';
    }
}

// Remaining Leave Fetch
if ($_POST['action'] == 'show_remaining_leave') {
    $sql = mysqli_query($conn, "SELECT * from total_add_leave where e_id = " . $_POST['e_id'] . " ");
    $row = mysqli_fetch_array($sql);
    $no = mysqli_num_rows($sql);

    if ($no > 0) {
        $r_leave = $row['total_leave'];
        echo $r_leave;
    } else {
        echo "0";
    }
}

// Password Chnage
if ($_POST['action'] == 'change_password') {
    $old_pass = hash('sha1', $_POST['old_pass']);
    $new_pass = hash('sha1', $_POST['new_pass']);
    $sql = mysqli_query($conn, "SELECT * from employee where e_id = " . $_POST['e_id'] . "  and e_password = '$old_pass' ");
    $no = mysqli_num_rows($sql);
//    $row = mysqli_fetch_array($sql);
    if ($no > 0) {
        $sql = mysqli_query($conn, "UPDATE employee SET e_password = '$new_pass' WHERE e_id = " . $_POST['e_id'] . " ");
        if ($sql) {
            echo 'true';
        } else {
            echo 'false';
        }
    } else {
        echo 'Invalid Password Entered';
    }

}


if ($_POST['action'] == 'view_salary') {

    function numberTowords($num)
    {
        $ones = array(
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen",
            15 => "fifteen",
            16 => "sixteen",
            17 => "seventeen",
            18 => "eighteen",
            19 => "nineteen"
        );

        $tens = array(
            1 => "ten",
            2 => "twenty",
            3 => "thirty",
            4 => "forty",
            5 => "fifty",
            6 => "sixty",
            7 => "seventy",
            8 => "eighty",
            9 => "ninety"
        );
        $hundreds = array(
            "hundred",
            "thousand",
            "million",
            "billion",
            "trillion",
            "quadrillion"
        ); //limit t quadrillion
        $num = number_format($num, 2, ".", ",");
        $num_arr = explode(".", $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach ($whole_arr as $key => $i) {
            if ($i < 20) {
                if (array_key_exists($i, $ones)) {
                    $rettxt .= $ones[$i];
                }
            } elseif ($i < 100) {
                $rettxt .= $tens[substr($i, 0, 1)];
                $rettxt .= " " . $ones[substr($i, 1, 1)];
            } else {
                $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                $rettxt .= " " . $tens[substr($i, 1, 1)];
                $rettxt .= " " . $ones[substr($i, 2, 1)];
            }
            if ($key > 0) {
                $rettxt .= " " . $hundreds[$key] . " ";
            }
        }
        if ($decnum > 0) {
            $rettxt .= " and ";
            if ($decnum < 20) {
                $rettxt .= $ones[$decnum];
            } elseif ($decnum < 100) {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= " " . $ones[substr($decnum, 1, 1)];
            }
        }
        return ucfirst($rettxt);
    }

    $months = $_POST['month'];
// Year
    $years = $_POST['year'];
// Admin Id
    $admin_id = $_POST['admin_id'];
// Month First Day
    $f_date = strtotime(date("1-$months-$years 00:00:00"));
    $month_name = date("M", $f_date);
// Month Last Day
    $t_date = strtotime(date("30-$months-$years 23:59:59"));
    $query = "SELECT * FROM staff_salary INNER JOIN employee ON employee.e_id = staff_salary.e_id INNER JOIN designation ON designation.designation_id = employee.designation WHERE staff_salary.e_id = '" . $_POST['e_id'] . "' AND staff_salary.salary_status = 1 AND staff_salary.admin_id = '$admin_id' AND staff_salary.staff_salary_date BETWEEN '$f_date' AND '$t_date'";
    $sql = mysqli_query($conn, $query);
    // if($_POST['e_id'] == 13){
    //     echo $query;
    // }
    $company_details = mysqli_query($conn, "SELECT * FROM company_admin INNER JOIN company_logo ON company_admin.admin_id = company_logo.admin_id WHERE  company_admin.admin_id = '$admin_id' ");
    $row = mysqli_fetch_array($sql);
    $com = mysqli_fetch_array($company_details);
    $logo_name = $com['logo_name'];
    $designation = $row['designation_name'];
    $emp_name = $row['emp_name'];
    $emp_cardid = $row['emp_cardid'];
    $company_name = $com['company_name'];
    $output = '<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<h4 class="payslip-title">Payslip for the month of ' . $month_name . " " . $years . '</h4>
									<div class="row">
										<div class="col-sm-6 m-b-20">
											<img src="../admin/company_document/' . $logo_name . '" width="130px" height="70px" alt="">
										</div>
										<div class="col-sm-6 m-b-20">
											<div class="invoice-details">
												<ul class="list-unstyled">
													<li>Salary Month: <span>' . $month_name . " " . $years . '</span></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 m-b-20">
											<ul class="list-unstyled">
												<li><h4 class="mb-0"><span>' . $emp_name . '</span></h4></li>
												<li><small><b>' . $designation . '</b></small></li>
												<li>Employee ID: ' . $emp_cardid . '</li>
											</ul>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div>
												<table class="table table-bordered">
													<tbody>
														<tr>
															<td><strong>Basic Salary</strong> <span class="float-right">' . $row['basic'] . '</span></td>
														</tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-bordered">
													<tbody>
														<tr>
															<td><strong>Earnings</strong></td>
														</tr>';
    if ($row['hra'] != 'not used') {
        $output .= '	<tr>
                                                                        <td><strong>House Rent Allowance (H.R.A.)</strong> <span class="float-right">' . $row['hra'] . '</span></td>
                                                                    </tr>';
    }
    if ($row['da'] != 'not used') {
        $output .= '	<tr>
                                                                        <td>Dearness Allowance (D.A.) <span class="float-right">' . $row['da'] . '</span></td>
                                                                    </tr>';
    }
    if ($row['conveyance'] != 'not used') {
        $output .= '	<tr>
                                                                        <td>Conveyance <span class="float-right">' . $row['conveyance'] . '</span></td>
                                                                    </tr>';
    }
    if ($row['allowance'] != 'not used') {
        $output .= '	<tr>
                                                                        <td>Allowance <span class="float-right">' . $row['allowance'] . '</span></td>
                                                                    </tr>';
    }
    if ($row['medical_allowance'] != 'not used') {
        $output .= '	<tr>
                                                                        <td><Medical Allowance (M.A.) <span class="float-right">' . $row['medical_allowance'] . '</span></td>
                                                                    </tr>';
    }
    $output .= '	<tr>
                                                                        <td>Insentive <span class="float-right">' . $row['incentive'] . '</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Over Time <span class="float-right">' . $row['over_time'] . '</span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
										<div class="col-sm-6">
											<div>
											<table class="table table-bordered">
													<tbody>
														<tr>
															<td>Per Day Salary <span class="float-right">' . $row['par_day_salary'] . '</span></td>
														</tr>
                                                    </tbody>
                                                </table>
												<table class="table table-bordered">
													<tbody>
														<tr>
															<td><strong>Deductions</strong></td>
														</tr>';

    if ($row['tds'] != 'not used') {
        $output .= '<tr>
															<td>Tax Deducted at Source (T.D.S.) <span class="float-right">' . $row['tds'] . '</span></td>
														</tr>';
    }
    if ($row['pf'] != 'not used') {
        $output .= '<tr>
															<td>Provident Fund <span class="float-right">' . $row['pf'] . '</span></td>
														</tr>';
    }
    if ($row['esi'] != 'not used') {
        $output .= '<tr>
															<td>ESI <span class="float-right">' . $row['esi'] . '</span></td>
														</tr>';
    }
    if ($row['prof_tax'] != 'not used') {
        $output .= '<tr>
															<td>Prof. Tax <span class="float-right">' . $row['prof_tax'] . '</span></td>
														</tr>';
    }
    if ($row['labour_welfare'] != 'not used') {
        $output .= '<tr>
															<td>Labour Welfare <span class="float-right">' . $row['labour_welfare'] . '</span></td>
														</tr>';
    }
    $output .= '<tr>
															<td>Leave Deduction <span class="float-right">' . $row['e_leave'] . '</span></td>
														</tr>
														<tr>
															<td>Late Fine <span class="float-right">' . $row['late_fine'] . '</span></td>
														</tr>
														<tr>
															<td>Break Fine <span class="float-right">' . $row['break_violation'] . '</span></td>
														</tr>
											    	</tbody>
												</table>
											</div>
										</div>
										<div class="col-sm-12">
											<table class="table table-bordered">
													<tbody>
														<tr>
														    <td width="50%"><strong>Total Earnings</strong> <span class="float-right"><strong>' . $row['earning'] . '</strong></span></td>
															<td width="50%"><strong>Total Deductions</strong> <span class="float-right"><strong>' . $row['deduction'] . '</strong></span></td>
														 </tr>
                                                    </tbody>
                                                </table>
										</div>
										<div class="col-sm-12">
											<p><strong>Net Salary: ' . round($row['net_salary'], 2) . '</strong> (' . numberTowords($row['net_salary']) . ')</p>
											<p><strong class="text-danger"><small>This is computer generated Copy, Not valid if not signed/stamped.</small></strong><i class="fa fa-download"></i></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
    echo $output;
}

if ($_POST['action'] == 'emp_profile') {
    $sql3 = mysqli_query($conn, "select * from employee_emergemcy_contact where e_id = '" . $_POST['e_id'] . "' ");
    $r3 = mysqli_fetch_array($sql3);

    $output1 = '<li>
                        <div class="title">Name</div>
                        <div class="text">' . $r3['person_name'] . '</div>
                    </li>
                    <li>
                        <div class="title">Relationship</div>
                        <div class="text">' . $r3['relationship'] . '</div>
                    </li>
                    <li>
                        <div class="title">Phone</div>
                        <div class="text">' . $r3['phone_number'] . '</div>
                    </li>';

    $sql2 = mysqli_query($conn, "select * from employee_profile where e_id = '" . $_POST['e_id'] . "' ");
    $r = mysqli_fetch_array($sql2);
    $sql5 = mysqli_query($conn, "select * from employee where e_id = '" . $_POST['e_id'] . "' ");
    $sr = mysqli_fetch_array($sql5);
    $output2 = '<li>
                        <div class="title">Birthday:</div>
                        <div class="text">' . $r['date_of_birth'] . '</div>
                    </li>
                    <li>
                        <div class="title">Address:</div>
                        <div class="text">' . $r['emp_address'] . '</div>
                    </li>
                    <li>
                        <div class="title">Gender:</div>
                        <div class="text">' . $sr['e_gender'] . '</div>
                    </li>
                    <li>
                        <div class="title">Nationality:</div>
                        <div class="text">' . $r['emp_nationality'] . '</div>
                    </li>
                    <li>
                        <div class="title">Religion:</div>
                        <div class="text">' . $r['emp_religion'] . '</div>
                    </li>
                    <li>
                        <div class="title">Marital status:</div>
                        <div class="text">' . $r['martial_status'] . '</div>
                    </li>';
    $sql3 = mysqli_query($conn, "select * from employee_bank_detail where e_id = '" . $_POST['e_id'] . "'");
    $r3 = mysqli_fetch_array($sql3);

    $output3 = '<li>
                    <div class="title">Bank name</div>
                    <div class="text">' . $r3['eb_name'] . '</div>
                </li>
                <li>
                    <div class="title">Bank account No.</div>
                    <div class="text">' . $r3['eb_account_number'] . '</div>
                </li>
                <li>
                    <div class="title">IFSC Code</div>
                    <div class="text">' . $r3['eb_ifsc_code'] . '</div>
                </li>
                <li>
                    <div class="title">Branch Name</div>
                    <div class="text">' . $r3['eb_branch_name'] . '</div>
                </li>
                <li>
                    <div class="title">PAN No</div>
                    <div class="text">' . $r3['eb_pan_no'] . '</div>
                </li>';
    $data = array();
    $data[0] = $output1;
    $data[1] = $output2;
    $data[2] = $output3;
    echo json_encode($data);
}

if($_POST['action'] == 'add_profile'){

    $date_of_birth = $_POST['date_of_birth'];
    $emp_address = mysqli_real_escape_string($conn, $_POST['emp_address']);
    $pin_code = $_POST['pin_code'];
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $emp_gender = mysqli_real_escape_string($conn, $_POST['emp_gender']);
    $alternate_number = $_POST['alternate_number'];
    $emp_nationality = mysqli_real_escape_string($conn, $_POST['emp_nationality']);
    $emp_religion = mysqli_real_escape_string($conn, $_POST['emp_religion']);
    $martial_status = mysqli_real_escape_string($conn, $_POST['martial_status']);

    $person_name = mysqli_real_escape_string($conn, $_POST['person_name']);
    $relationship = mysqli_real_escape_string($conn, $_POST['relationship']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    $eb_name = mysqli_real_escape_string($conn, $_POST['eb_name']);
    $eb_account_number = mysqli_real_escape_string($conn, $_POST['eb_account_number']);
    $eb_ifsc_code = $_POST['eb_ifsc_code'];
    $eb_branch_name = mysqli_real_escape_string($conn, $_POST['eb_branch_name']);
    $eb_pan_no = mysqli_real_escape_string($conn, $_POST['eb_pan_no']);
    $eb_added_time = time();
    $data_update_status = mysqli_real_escape_string($conn, $_POST['data_update_status']);
    $employee_detail_update = mysqli_real_escape_string($conn, $_POST['employee_detail_update']);
    $e_id = $_POST['e_id'];
    $admin_id = $_POST['admin_id'];
    $eb_update_status = mysqli_real_escape_string($conn, $_POST['eb_update_status']);
    $sql = $conn->query("SELECT * FROM employee WHERE emp_update_status = 1 AND e_id = '$e_id' ");
    if($sql->num_rows > 0){
        $query1 = $conn->query("UPDATE  employee_profile SET `e_id` = '$e_id', `admin_id` = '$admin_id', `date_of_birth` =  '$date_of_birth', `emp_address` = '$emp_address', `pin_code` = '$pin_code',`state` = '$state', `alternate_number` = '$alternate_number', `emp_nationality` = '$emp_nationality', `emp_religion` = '$emp_religion', `martial_status` = '$martial_status', `employee_detail_update` = '$employee_detail_update' WHERE e_id = '$e_id' AND admin_id = '$admin_id'");
        $query2 = $conn->query("UPDATE  employee_emergemcy_contact SET `e_id` = '$e_id', `admin_id` = '$admin_id', `person_name` = '$person_name', `relationship` = '$relationship', `phone_number` = '$phone_number',`data_update_status` = '$data_update_status' WHERE e_id = '$e_id' AND admin_id = '$admin_id'");
        $query3 = $conn->query("UPDATE  employee_bank_detail SET `e_id` = '$e_id',`admin_id` = '$admin_id', `eb_name` = '$eb_name',`eb_account_number` = '$eb_account_number',`eb_ifsc_code` = '$eb_ifsc_code',`eb_branch_name` = '$eb_branch_name',`eb_pan_no` = '$eb_pan_no',`eb_update_status` = '$eb_update_status',`eb_added_time` = '$eb_added_time' WHERE e_id = '$e_id' AND admin_id = '$admin_id'");
        $query4 = $conn->query("UPDATE employee SET e_gender = '$emp_gender' WHERE e_id = '$e_id' AND admin_id = '$admin_id' ");
        if($query1 && $query2 && $query3 && $query4){
            echo 'success';
        }else{
            echo 'faild';
        }
    }else {
        $query1 = $conn->query("insert into employee_profile(`e_id`, `admin_id`, `date_of_birth`, `emp_address`, `pin_code`, `state`, `alternate_number`, `emp_nationality`, `emp_religion`, `martial_status`, `employee_detail_update`)values('$e_id','$admin_id','$date_of_birth','$emp_address','$pin_code','$state','$alternate_number','$emp_nationality','$emp_religion','$martial_status','$employee_detail_update') ");
        $query2 = $conn->query("insert into employee_emergemcy_contact(`e_id`, `admin_id`, `person_name`, `relationship`, `phone_number`,`data_update_status`)values('$e_id','$admin_id','$person_name','$relationship','$phone_number','$data_update_status')");
        $query3 = $conn->query("insert into employee_bank_detail(`e_id`,`admin_id`,`eb_name`, `eb_account_number`, `eb_ifsc_code`,`eb_branch_name`, `eb_pan_no`, `eb_update_status`,`eb_added_time`)values('$e_id','$admin_id','$eb_name','$eb_account_number','$eb_ifsc_code','$eb_branch_name','$eb_pan_no','$eb_update_status','$eb_added_time') ");
        $query4 = $conn->query("update employee set emp_update_status = '1' , e_gender = '$emp_gender' where e_id = '$e_id' AND admin_id = '$admin_id' ");
        if($query1 && $query2 && $query3 && $query4){
            echo 'success';
        }else{
            echo 'faild';
        }
    }
}

if($_POST['action'] == 'getData'){
$e_id = $_POST['e_id'];
    $sql = $conn->query("SELECT * FROM employee_profile WHERE e_id = '$e_id' ");
    $employe_profile = $sql->fetch_assoc();
    $sql = $conn->query("SELECT * FROM employee_emergemcy_contact WHERE e_id = '$e_id' ");
    $emergency_contact = $sql->fetch_assoc();
    $sql = $conn->query("SELECT * FROM employee_bank_detail WHERE e_id = '$e_id' ");
    $emp_bank = $sql->fetch_assoc();
    $sql = $conn->query("SELECT * FROM employee WHERE e_id = '$e_id' ");
    $employee = $sql->fetch_assoc();
    if($employee['emp_update_status'] == 0){
        echo 'new_user';
    }else{
        $arr = array(
            'profile' => $employe_profile,
            'emergency' => $emergency_contact,
            'bank' => $emp_bank,
            'employee' => $employee,
        );
        echo json_encode($arr);
    }

}

