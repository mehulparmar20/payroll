<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Windson Payroll | <?php echo $page; ?></title>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="app/img/payrollfavicon.png">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="app/css/bootstrap.min.css">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" type="text/css" href="links/font-awesome.min.css">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" rel="stylesheet">
<!--        <link href="http://3.92.193.2/assets/css/style.css" rel="stylesheet">-->
        <script src="https://kit.fontawesome.com/69d52db2ce.js" crossorigin="anonymous"></script>

        <!-- Lineawesome CSS -->
        <!--<link rel="stylesheet" href="app/css/line-awesome.min.css">-->
        <!--hover css-->
        <link rel="stylesheet" type="text/css" href="app/css/hover.css">
        <!-- Chart CSS -->
<!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">-->
        <link rel="stylesheet" href="app/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="app/css/fixedColumns.bootstrap4.min.css">


        <!--<link rel="stylesheet" href="app/css/bootstrap-datetimepicker.min.css">-->
        <link rel="stylesheet" href="app/css/bootstrap-formhelpers.min.css">
        <!--<link rel="stylesheet" href="app/css/daterangepicker/daterangepicker.css">-->
        <link rel="stylesheet" href="app/css/custom.css">
        <!-- Main CSS -->
        <link rel="stylesheet" href="app/css/style.css">
        <!--select2-->
        <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">

        <!--<link href="../assets/wizard/css/jquery.dataTable.min.css" rel="stylesheet">-->
        <!--<link rel="stylesheet" href="links/select2.min.css">-->
        <style>
            .select-values {
                color: white; // color of the selected option
            }

            select option {
                color: black; // color of all the other options
            }
        </style>
    </head>

    <body  id="style-13">
    <div id="overlay" class="wrap-loader" style="display:none;">
        <div class="spinner"></div>
        <br/>
        Please wait...
    </div>
        <!-- Main Wrapper -->
        <div class="main-wrapper">

            <!-- Header -->
            <div class="header">
                <?php
                if(!isset($page)){
                    $page = '';
                }
                include '../dbconfig.php';
                include 'api/index.php';
                $api = new Util();
                $device_status = $api->checkDeviceStatus($_SESSION['devIndex'],$_SESSION['device_username'],$_SESSION['device_password']);
                $sql = mysqli_query($conn, "SELECT * FROM company_admin where admin_id = " . $_SESSION['admin_id'] . " ");
                $com_row = mysqli_fetch_assoc($sql);
                    $company_name = $com_row['company_name'];
                    $emp_remaining = $com_row['remainingemployee'];
                          
                if (basename($_SERVER['PHP_SELF']) != 'salary.php') {
                    unset($_SESSION['accounting']);
                }
                $sql = mysqli_query($conn, "SELECT * FROM access_view where admin_id = " . $_SESSION['admin_id'] . " ");
                while ($row = mysqli_fetch_assoc($sql)) {
                    $employee = $row['employees'];
                    $employee_view = $row['employee_view'];
                    $manage_employee = $row['manage_employee'];
                    $hloidays = $row['holidays'];
                    $department = $row['department'];
                    $designation = $row['designation'];
                    $announcement = $row['announcement'];
                    $leave_module = $row['leaves_module'];
                    $leave_view = $row['leave_view'];
                    $attendance_module = $row['attendance_module'];
                    $attendance_view = $row['attendance_view'];
                    $attendance_info = $row['attendance_info'];
                    $break_module = $row['break_module'];
                    $break_view = $row['break_view'];
                    $break_info = $row['break_info'];
                    $payroll = $row['payroll'];
                    $employee_salary = $row['employee_salary'];
                    $salary_settings = $row['salary_settings'];
                    $remaining_leave = $row['remaining_leaves'];
                    $policy = $row['policy'];
                    $letters_module = $row['letters'];
                    $joining = $row['joining'];
                    $experience = $row['experience'];
                    $termination = $row['termination'];
                    $resignation = $row['resignation'];
                    $administration = $row['administration'];
                    $company_profile = $row['company_profile'];
                    $company_document = $row['company_document'];
                    $company_time = $row['company_time'];
                    $company_logo = $row['company_logo'];
                    $leave_type = $row['leave_type'];
                    $break_off = $row['system_control'];
                    $add_user = $row['add_user'];
                    $working_days = $row['working_days'];
                    $manage_pasword = $row['manage_password'];
                    $user_user = 1;
                }
                if(isset($_SESSION['user_id'])){
                    $user = mysqli_query($conn, "SELECT * FROM add_users where user_id = " . $_SESSION['user_id'] . " ");
                    $val = mysqli_fetch_array($user);
                        $employee_user = $val['employee'];
                        $leaves_user= $val['leave'];
                        $attendance = $val['attendance'];
                        $break = $val['break'];
                        $payroll_user = $val['payroll'];
                        $letters_user = $val['letters'];
                        $user_user = $val['administration'];
                    
                }
                ?>
                <!-- Logo -->
                <div style="background-color: #f6ffed" class="header-left">
                    <a href="index.php"  class="logo">
                        <center><img src="app/img/payroll.png" style="border-radius: 0%" width="150px" height="65px" alt=""></center>
                    </a>
                </div>
                <!-- /Logo -->
                <a id="toggle_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
                <!-- Header Title -->
                <div class="page-title-box">
                    <h3><b><?php echo $company_name; ?></b></h3> <span class="text-light"> Device Status : <span id="device_status"><?php echo ucfirst($device_status);?></span></span>
                </div>
                <!-- /Header Title -->
                <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- Header Menu -->
                <ul class="nav user-menu">
                    <li class="nav-item">
                        <div class="top-nav-search">
                            <form>
                                <select id="shift" onchange="shiftchange()" class="form-control select-values">
                                   <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM company_time where admin_id = " . $_SESSION['admin_id'] . " ");
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        if($no == 1 && !isset($_SESSION['shift_id'])){?>
                                        <option value="<?php echo $row['time_id']; ?>" selected><?php echo $row['shift_no'] . " ( " . $row['company_in_time'] . " To " . $row['company_out_time'] . " )"; ?></option>
                                    <?php }elseif (isset($_SESSION['shift_id']) && $row['time_id'] == $_SESSION['shift_id'] ) { ?>
                                            <option value="<?php echo $row['time_id']; ?>" selected><?php echo $row['shift_no'] . " ( " . $row['company_in_time'] . " To " . $row['company_out_time'] . " )"; ?></option>
                                            <?php }else{ ?>
                                            <option value="<?php echo $row['time_id']; ?>"><?php echo $row['shift_no'] . " ( " . $row['company_in_time'] . " To " . $row['company_out_time'] . " )"; ?></option>
                                    <?php }
                                         $no++; } ?>
                                </select>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown has-arrow main-drop">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
<!--                            <span class="user-img"><i class="fa fa-tablet" style="color: #FFFFFF;font-size: 18px" aria-hidden="true"></i>-->
<!--                            <span class="status online"></span></span>-->
                            <span> <?php if (isset($_SESSION['admin_name'])) {
                    echo $_SESSION['admin_name'];
                } else {
                    echo $_SESSION['user_name'];
                } ?></span>

                        </a>
                        <div class="dropdown-menu">
<?php  if (isset($_SESSION['user_id']) && $user_user == "1") { ?>
                                <a class="dropdown-item" href="settings.php">Company Profile</a>
<?php } elseif(isset($_SESSION['admin_name'])) { ?>
             <a class="dropdown-item" href="settings.php">Company Profile</a>
<?php         } else { ?>
             <a class="dropdown-item" style="cursor:not-allowed" title="You Have Not Access To Change Company Details" href="#">Company Profile</a>
   <?php      } ?>
                            <a class="dropdown-item" href="../logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
                <!-- /Header Menu -->

                <!-- Mobile Menu -->
                <div class="dropdown mobile-user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></a>
                    <div class="dropdown-menu dropdown-menu-right">
<?php if ($user_user == "1") { ?>
                            <a class="dropdown-item" href="settings.php">Company Profile</a>
<?php } ?>
                        <a class="dropdown-item" href="../logout.php">Logout</a>
                    </div>
                </div>
                <!-- /Mobile Menu -->

            </div>
            <!-- /Header -->

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
                    <div id="sidebar-menu" class="sidebar-menu">
                        <ul>
                            <?php if(!isset($_SESSION['substatus'])){ ?>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') { ?> class="active" <?php } ?>>
                                <a href="index.php">
                                    <div class="icona">
                                        <i class="fa fa-tachometer" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-tachometer" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b>Dashboard</b></span>
                                        <span><b>Dashboard</b></span>
                                    </div> 

                                </a>
                            </li>
<?php if($employee == "1") { if( isset($_SESSION['user_id']) && $employee_user == "1" || isset($_SESSION['admin_name'])){ ?>

                            <li class="submenu">
                                <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'employees.php' || basename($_SERVER['PHP_SELF']) == 'manage_employee.php' || basename($_SERVER['PHP_SELF']) == 'holidays.php' || basename($_SERVER['PHP_SELF']) == 'departments.php' || basename($_SERVER['PHP_SELF']) == 'designations.php' || basename($_SERVER['PHP_SELF']) == 'announcement.php') { ?> class="sub_active" <?php } ?> >
                                    <div class="icona">
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span> <b>Employees</b></span><br>
                                        <span> <b>Employees</b></span>
                                    </div>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul style="display: none;">
<?php if ($employee_view == "1") { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'employees.php') { ?> class="active" <?php } ?> href="employees.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-users" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>View Employees</b></span><br>
                                                </div>
                                            </a>
                                        </li>
<?php } if ($manage_employee == "1") { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'manage_employee.php') { ?> class="active" <?php } ?> href="manage_employee.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-user-circle-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>Employee history</b></span><br>
                                                </div>
                                            </a>
                                        </li>
<?php } if ($hloidays == "1") { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'holidays.php') { ?> class="active" <?php } ?> href="holidays.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-calendar" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>Add Holidays</b></span><br>
                                                </div>
                                            </a>
                                        </li>
<?php }  if ($department == "1") { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'departments.php') { ?> class="active" <?php } ?> href="departments.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-building" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>Departments</b></span><br>
                                                </div>
                                            </a>
                                        </li>
 <?php } if ($designation == "1") { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'designations.php') { ?> class="active" <?php } ?> href="designations.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-calendar-plus-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>Designations</b></span><br>
                                                    <span><i class="fa fa-calendar-plus-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp; <b>Designations</b></span>
                                                </div>
                                            </a>
                                        </li>
<?php } if ($announcement == 1) { ?>
                                        <li>
                                            <a <?php if (basename($_SERVER['PHP_SELF']) == 'announcement.php') { ?> class="active" <?php } ?> href="announcement.php">
                                                <div class="namea">
                                                    <span><i class="fa fa-bullhorn" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Announcement</b></span><br>
                                                    <span><i class="fa fa-bullhorn" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Announcement</b></span>
                                                </div>
                                            </a>
                                        </li>
<?php } ?>
                                </ul>
                            </li>

<?php } } if ($payroll == "1") {
                if(isset($_SESSION['user_id']) && $payroll_user == 1 || isset($_SESSION['admin_name'])){ ?>
                                <li class="submenu">
                                    <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'salary.php' || basename($_SERVER['PHP_SELF']) == 'salary_setting.php' || basename($_SERVER['PHP_SELF']) == 'remaining_leave.php' || basename($_SERVER['PHP_SELF']) == 'policies.php') { ?> class="sub_active" <?php } ?>>
                                        <div class="icona">
                                            <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i><br>
                                            <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i>
                                        </div>
                                        <div class="namea">
                                            <span><b> Payroll</b> </span><br>
                                            <span><b> Payroll</b> </span>
                                        </div> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul style="display: none;">
<?php if ($employee_salary == 1) { ?>
                                        <li>
                                            <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'salary.php') { ?> class="active" <?php } ?> id="salary_auth" data-toggle="modal" data-target="#accounting_login">
                                                <div class="namea">
                                                    <span><i class="fa fa-money" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b> Employee Salary</b> </span>
                                                </div> 
                                            </a>
                                        </li>
    <?php } if ($remaining_leave == "1") { ?>
                                        <li>
                                            <a href="remaining_leave.php" <?php if (basename($_SERVER['PHP_SELF']) == 'remaining_leave.php') { ?> class="active" <?php } ?>>
                                                <div class="namea">
                                                    <span><i class="fa fa-calendar-plus-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b> Credit Leave</b> </span>
                                                </div> 
                                            </a>
                                        </li>
    <?php } if ($salary_settings == "1") { ?>
                                        <li>
                                            <a href="salary_setting.php" <?php if (basename($_SERVER['PHP_SELF']) == 'salary_setting.php') { ?> class="active" <?php } ?>>
                                                <div class="namea">
                                                    <span><i class="fa fa-cog" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Setup Salary</b> </span>
                                                </div> 
                                            </a>
                                        </li>
    <?php } if ($policy == 1) { ?>
                                        <!--<li> -->
                                        <!--    <a <?php if (basename($_SERVER['PHP_SELF']) == 'allowances.php') { ?> class="active" <?php } ?> href="allowances.php">-->
                                        <!--        <div class="namea">-->
                                        <!--            <span><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:15px;"></i> <b>Allowances</b></span>-->
                                        <!--        </div>-->
                                        <!--    </a>-->
                                        <!--</li>-->
                                        <!--<li> -->
                                        <!--    <a <?php if (basename($_SERVER['PHP_SELF']) == 'deductions.php') { ?> class="active" <?php } ?> href="deductions.php">-->
                                        <!--        <div class="namea">-->
                                        <!--            <span><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:15px;"></i> <b>Deductions</b></span>-->
                                        <!--        </div>-->
                                        <!--    </a>-->
                                        <!--</li>-->
    <?php } ?>
                                    </ul>
                                </li>

<?php } } if ($attendance_module == 1) {
            if(isset($_SESSION['user_id']) && $attendance == "1" || isset($_SESSION['admin_name'])){ ?>
                                <li class="submenu">
                                    <a <?php if (basename($_SERVER['PHP_SELF']) == 'attendance.php' || basename($_SERVER['PHP_SELF']) == 'attendance_info.php') { ?> class="sub_active" <?php } ?> href="#" >
                                        <div class="icona">
                                            <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size:15px;"></i><br>
                                            <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size:15px;"></i>
                                        </div>
                                        <div class="namea">
                                            <span><b> Attendance</b> </span><br>
                                            <span><b> Attendance</b> </span>
                                        </div> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul style="display: none;">
<?php if ($attendance_view == 1) { ?>
                                        <li>
                                            <a href="attendance.php" <?php if (basename($_SERVER['PHP_SELF']) == 'attendance.php') { ?> class="active" <?php } ?> >
                                                <div class="namea">
                                                    <span><i class="fa fa-calendar-check-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>&nbsp; Attendance View</b> </span><br> 
                                                </div> 
                                            </a>
                                        </li>
<?php } if ($attendance_info == 1) { ?>
                                        <li> 
                                            <a href="attendance_info.php" <?php if (basename($_SERVER['PHP_SELF']) == 'attendance_info.php') { ?> class="active" <?php } ?>>
                                                <div class="namea">
                                                    <span><i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i>&nbsp;<b>&nbsp; Attendance Time</b></span><br>
                                                </div>
                                            </a>
                                        </li>
<?php } ?>
                                    </ul>
                                </li>
<?php } } if ($break_module == "1") {  if(isset($_SESSION['user_id']) && $break == "1" || isset($_SESSION['admin_name'])){ ?>
                                <li class="submenu">
                                    <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'break_report.php' || basename($_SERVER['PHP_SELF']) == 'break_info.php') { ?> class="sub_active" <?php } ?>>
                                        <div class="icona">
                                            <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size:15px;"></i><br>
                                            <i class="fa fa-calendar-plus-o" aria-hidden="true" style="font-size:15px;"></i>
                                        </div>
                                        <div class="namea">
                                            <span><b> Break</b> </span><br>
                                            <span><b> Break</b> </span>
                                        </div> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul style="display: none;">
<?php if ($break_view == 1) { ?>
                                        <li>
                                            <a href="break_report.php" <?php if (basename($_SERVER['PHP_SELF']) == 'break_report.php') { ?> class="active" <?php } ?>>
                                                <div class="namea">
                                                    <span><i class="fa fa-calendar-check-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>&nbsp; Break View</b> </span>
                                                </div> 
                                            </a>
                                        </li>
<?php } if ($break_info == 1) { ?>
                                        <li> 
                                            <a href="break_info.php" <?php if (basename($_SERVER['PHP_SELF']) == 'break_info.php') { ?> class="active" <?php } ?>>
                                                <div class="namea">
                                                    <span><i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i>&nbsp; <b>&nbsp;Break Time</b></span>
                                                </div>
                                            </a>
                                        </li>
        <?php } ?>
                                    </ul>
                                </li>
<?php } } if ($leave_module == 1) { if(isset($_SESSION['user_id']) && $leaves_user == 1 || isset($_SESSION['admin'])){  ?>
                                <li class="submenu">
                                    <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'leaves.php' || basename($_SERVER['PHP_SELF']) == 'leave_report.php') { ?> class="sub_active" <?php } ?>>
                                        <div class="icona">
                                            <i class="fa fa-user-plus" aria-hidden="true" style="font-size:15px;"></i><br>
                                            <i class="fa fa-user-plus" aria-hidden="true" style="font-size:15px;"></i>
                                        </div>
                                        <div class="namea">
                                            <span><b> Leave</b> </span><br>
                                            <span><b> Leave</b> </span>
                                        </div> 
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul style="display: none;">
<?php  if ($leave_view == 1) { ?>
                                        <li>
                                            <a href="leaves.php" <?php if (basename($_SERVER['PHP_SELF']) == 'leaves.php') { ?> class="active" <?php } ?>>
                                                <span><i class="fa fa-calendar-check-o" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b> Leave View</b></span>
                                            </a>
                                        </li>
 <?php } ?>
                                    </ul>
                                </li>
<?php } } if($letters_module == 1) {
                if(isset($_SESSION['user_id']) && $letters_user == 1 || isset($_SESSION['admin_name'])) { ?>
                            <li class="submenu">
                                <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'joining_latter.php' || basename($_SERVER['PHP_SELF']) == 'resignation.php' || basename($_SERVER['PHP_SELF']) == 'experience.php' || basename($_SERVER['PHP_SELF']) == 'termination.php') { ?> class="sub_active" <?php } ?>>
                                    <div class="icona">
                                        <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i><br>
                                        <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b> Letters</b> </span><br>
                                        <span><b> Letters</b> </span>
                                    </div> 
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
<?php  if ($joining == 1) { ?>
                                    <li>
                                        <a href="joining_latter.php" <?php if (basename($_SERVER['PHP_SELF']) == 'joining_latter.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><i class="fa fa-bullhorn" aria-hidden="true" style="font-size:15px;"></i>&nbsp;<b>Joining</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($resignation == 1) { ?>
                                    <li>
                                        <a href="resignation.php" <?php if (basename($_SERVER['PHP_SELF']) == 'resignation.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><i class="fa fa-graduation-cap" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Resignations</b></span><br>
                                            </div>
                                        </a>    
                                    </li>
<?php } if ($experience == 1) { ?>
                                    <li>
                                        <a href="experience.php" <?php if (basename($_SERVER['PHP_SELF']) == 'experience.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><i class="fa fa-briefcase" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Experience</b></span><br>
                                            </div>
                                        </a>	
                                    </li>
<?php } if ($termination == 1) { ?>
                                    <li>
                                        <a href="termination.php" <?php if (basename($_SERVER['PHP_SELF']) == 'termination.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><i class="fa fa-times-circle" aria-hidden="true"style="font-size:15px;"></i>&nbsp;<b>Termination</b></span><br>
                                            </div>
                                        </a>	
                                    </li>
<?php } ?>
                                </ul>        
<?php } }  if($administration == "1"){
             if(isset($_SESSION['user_id']) && $user_user == "1" || isset($_SESSION['admin_name'])){  ?>
                            <li class="submenu">
                                <a href="#" <?php if (basename($_SERVER['PHP_SELF']) == 'company_document.php' || basename($_SERVER['PHP_SELF']) == 'add_user.php' || basename($_SERVER['PHP_SELF']) == 'company_logo.php' || basename($_SERVER['PHP_SELF']) == 'settings.php' || basename($_SERVER['PHP_SELF']) == 'plan.php' || basename($_SERVER['PHP_SELF']) == 'company_time.php' || basename($_SERVER['PHP_SELF']) == 'leave_type.php' || basename($_SERVER['PHP_SELF']) == 'compose.php' || basename($_SERVER['PHP_SELF']) == 'company_benefits.php' || basename($_SERVER['PHP_SELF']) == 'company_document.php' || basename($_SERVER['PHP_SELF']) == 'working_days.php' || basename($_SERVER['PHP_SELF']) == 'change_password.php' || basename($_SERVER['PHP_SELF']) == 'system_control.php' || basename($_SERVER['PHP_SELF']) == 'employee_of_month.php' ) { ?> class="sub_active" <?php } ?>>
                                    <div class="icona">
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span> <b>Administration</b></span><br>
                                        <span> <b>Administration</b></span>
                                    </div>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul style="display: none;">
<?php  if ($company_profile == "1") { ?>
                                    <li> 
                                        <a href="settings.php" <?php if (basename($_SERVER['PHP_SELF']) == 'settings.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b> <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i> Company Profile</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($company_document == 1) { ?>
                                    <li> 
                                        <a href="company_document.php" <?php if (basename($_SERVER['PHP_SELF']) == 'company_document.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-paperclip" aria-hidden="true" style="font-size:15px;"></i> Document</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($company_time == "1") { ?>
                                    <li> 
                                        <a href="company_time.php" <?php if (basename($_SERVER['PHP_SELF']) == 'company_time.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i> Company Time</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($leave_type == "1") { ?>
                                    <li> 
                                        <a href="leave_type.php" <?php if (basename($_SERVER['PHP_SELF']) == 'leave_type.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i> Leave Type</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } ?>
                                    <li> 
                                        <a href="compose.php" <?php if (basename($_SERVER['PHP_SELF']) == 'compose.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-paper-plane" aria-hidden="true" style="font-size:15px;"></i> Email</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php  if ($add_user == "1") { ?>
                                    <li> 
                                        <a href="add_user.php" <?php if (basename($_SERVER['PHP_SELF']) == 'add_user.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-user-plus" aria-hidden="true" style="font-size:15px;"></i> Add User</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($company_logo == "1") { ?>
                                    <li> 
                                        <a href="company_logo.php" <?php if (basename($_SERVER['PHP_SELF']) == 'company_logo.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-camera" aria-hidden="true" style="font-size:15px;"></i> Company Logo</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($working_days == "1") { ?>
                                    <li> 
                                        <a href="working_days.php" <?php if (basename($_SERVER['PHP_SELF']) == 'working_days.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-calendar-check-o" aria-hidden="true" style="font-size:15px;"></i> Working Days</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } if ($break_off == "1") { ?>
                                    <li> 
                                        <a href="system_control.php" <?php if (basename($_SERVER['PHP_SELF']) == 'system_control.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-calendar-check-o" aria-hidden="true" style="font-size:15px;"></i> Break Off Employee</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php }  ?>
                                    <li> 
                                        <a href="autotime.php" <?php if (basename($_SERVER['PHP_SELF']) == 'autotime.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-calendar-check-o" aria-hidden="true" style="font-size:15px;"></i> Automate User</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php if ($manage_pasword == "1") { ?>
                                    <li> 
                                        <a href="change_password.php" <?php if (basename($_SERVER['PHP_SELF']) == 'change_password.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-lock" aria-hidden="true" style="font-size:15px;"></i> Manage Password</b></span><br>
                                            </div>
                                        </a>
                                    </li>
<?php } ?>
                                    <!--<li>-->
                                    <!--    <a href="alert.php" <?php// if (basename($_SERVER['PHP_SELF']) == 'alert.php') { ?> class="active" <?php// } ?>>-->
                                    <!--        <div class="namea">-->
                                    <!--            <span><b><i class="fa fa-lock" aria-hidden="true" style="font-size:15px;"></i>Email Notification</b></span><br>-->
                                    <!--        </div>-->
                                    <!--    </a>-->
                                    <!--</li>-->
                                    <li>
                                        <a href="plan.php" <?php if (basename($_SERVER['PHP_SELF']) == 'plan.php') { ?> class="active" <?php } ?>>
                                            <div class="namea">
                                                <span><b><i class="fa fa-credit-card" aria-hidden="true" style="font-size:15px;"></i>Payment Details</b></span><br>
                                            </div>
                                        </a>
                                    </li>
                                    <br>
                                    <br>
                                </ul>
                            </li>
<?php } } }else{ ?>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == 'plan.php') { ?> class="active" <?php } ?>>
                                <a href="plan.php">
                                    <div class="icona">
                                        <i class="fa fa-lock" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-lock" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b>Subscription Plan</b></span>
                                        <span><b>Subscription Plan</b></span>
                                    </div> 

                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Sidebar -->
            <!-- Add or edit data model here  -->
            <div id="accounting_login" class="modal fade bs-example-modal-sm" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content  ">
                        <div class="modal-header">
                            <h5 class="modal-title">Enter The Accounting Passowrd</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input class="form-control" name="password" id="password" type="password">
                            </div>
                            <!--<div class="text-right">-->
                                <!--<a href="password_change.php">Click here to Reset Password</a>-->
                            <!--</div>-->
                            <div style="padding-top: 8px" class="text-center">
                                <span class="text-danger" id="error-message"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                            <button type="submit" onclick="salary_auth()" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                    <!-- declare admin_id for global use -->
                    <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                    <input type="hidden" id="page" name="page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>" >
                    <!-- // declare admin_id for global use -->
