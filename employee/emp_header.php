<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Windson Payroll">
        <meta name="facility" content="I-Card for Attendance and Break">
        <meta name="Benifit" content="monthly salary on one click">
        <title>Windson Payroll</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="app/img/payrollfavicon.png">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="app/css/bootstrap.min.css">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" type="text/css" href="links/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="app/js/69d52db2ce.js" crossorigin="anonymous"></script>
        <!-- Lineawesome CSS -->
        <!--<link rel="stylesheet" href="app/css/line-awesome.min.css">-->
        <!--hover css-->
        <link rel="stylesheet" type="text/css" href="app/css/hover.css">
        <!-- Chart CSS -->
        <link rel="stylesheet" href="app/plugins/morris/morris.css">
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
        <!-- Main CSS -->
        <link rel="stylesheet" href="app/css/style.css">
        <link rel="stylesheet" href="app/css/custom.css">
        <!--Datatable-->
        <link href="app/bootstrap-material-design-master/css/addons/datatables.min.css" rel="stylesheet">
         <!-- DataTables Select CSS -->
        <link href="app/bootstrap-material-design-master/css/addons/datatables-select.min.css" rel="stylesheet">
    </head>
<?php
	$e_id = $_SESSION['e_id'];
	$result = mysqli_query($conn,"SELECT * FROM employee INNER JOIN company_admin ON company_admin.admin_id = employee.admin_id where employee.e_id = '$e_id' ");
    $e = mysqli_fetch_array($result);
    $emp_name = $e['e_firstname'].' '.$e['e_lastname'];
    $emp_profile = $e['employee_profile'];
    $cn = $e['company_name'];
    $portalPunch = $e['portanPunch'];
    $shift = $e['shift_no'];
    $_SESSION['shift_no'] = $shift;
    $query = mysqli_query($conn, "select *  from company_time where time_id = '$shift' ");
    while($ron = mysqli_fetch_array($query))
    {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }
    date_default_timezone_set($timezone);
    
    $sql = "SELECT * FROM employee_profile WHERE e_id = '$e_id' ";
    $result = $conn->query($sql);
    $e_data = $result->fetch_assoc();
    $birth = strtotime($e_data['date_of_birth']);
    $birth = date('d-m',$birth);
    if(date('d-m') == $birth){
        $birth_status = 'yes';
    }else{
        $birth_status = 'no';
    }
    ?>

    <body>
    <div id="overlay" class="wrap-loader" style="display:none;">
        <div class="spinner"></div>
        <br/>
        Please wait...
    </div>
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Header -->
            <div class="header">
                <!-- Logo -->
                <div style="background: white" class="header-left">
                    <a href="home.php" class="logo">
                        <img  src="app/img/logo.png" width="150" height="66" alt="Windson Payroll">
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
                    
					<h3><?php echo $cn; ?></h3>
                </div>
                <!-- /Header Title -->
                <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- Header Menu -->
                <ul class="nav user-menu">
                    <li class="nav-item dropdown has-arrow main-drop">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <span class="user-img"><img src="employee_profile/<?php echo $emp_profile; ?>" alt="">
                            <span class="status online"></span></span>
                            <span><?php echo $emp_name; ?></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="profile.php">My Profile</a>
                            <a class="dropdown-item" href="change-password.php">Change Password</a>
                            <a class="dropdown-item" href="../logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
                <!-- /Header Menu -->

                <!-- Mobile Menu -->
                <div class="dropdown mobile-user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
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
                            <li class="menu-title"> 
                                <span><b>Main</b></span>
                            </li>
                            <li>
                                <a href="home.php">
                                    <div class="icona">
                                        <i class="fa fa-tachometer" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-tachometer" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b> Dashboard</b></span>
                                        <span><b> Dashboard</b></span>
                                    </div> 

                                </a>

                            </li>

                            <li class="menu-title"> 
                                <span><b>Employees</b></span>
                            </li>
                            <li>
                                <a href="profile.php">
                                    <div class="icona">
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-user" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span> <b>Profile</b></span><br>
                                        <span> <b>Profile</b></span>
                                    </div>

                                </a>
                            </li>
                            <li>

                                <a href="leaves-employee.php">
                                    <div class="icona">
                                        <i class="fa fa-calendar-times-o" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-calendar-times-o" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span> <b>Leaves</b></span><br>
                                        <span> <b>Leaves</b></span>
                                    </div>
                                </a>
                            </li>		

                            <li>
                                <a href="attendance-employee.php">
                                    <div class="icona">
                                        <i class="fa fa-calendar" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-calendar" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span> <b>Attendance</b></span><br>
                                        <span> <b>Attendance</b></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="break_report.php">
                                    <div class="icona">
                                        <i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-clock-o" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b> Monthly Break Report</b> </span><br> 
                                        <span><b> Monthly Break Report</b> </span>
                                    </div> 
                                </a>
                            </li>
                            <li>
                            <li>
                                <a href="break_info.php">
                                    <div class="icona">
                                        <i class="fa fa-info-circle" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-info-circle" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b> Break Details</b> </span><br> 
                                        <span><b> Break Details</b> </span>
                                    </div> 
                                </a>
                            </li>
                            <li>
  <!--                              <a href="employee_salary.php" >-->
                                <a href="employee_salary.php" data-toggle="modal" data-target="#view_salary">
                                    <div class="icona">
                                        <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-money" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b> Salary Report</b> </span><br> 
                                        <span><b> Salary Report</b> </span>
                                    </div> 
                                </a>
                            </li>
                            <li>
                                <a href="resignation.php">
                                    <div class="icona">
                                        <i class="fa fa-pencil-square" aria-hidden="true" style="font-size:15px;"></i> 
                                        <i class="fa fa-pencil-square" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea"> 
                                        <span><b>Apply Resignation</b></span><br>
                                        <span><b>Apply Resignation</b></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="file-manager.php">
                                    <div class="icona">
                                        <i class="fa fa-folder" aria-hidden="true" style="font-size:15px;"></i> 
                                        <i class="fa fa-folder" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea"> 
                                        <span><b>File Manager</b></span><br>
                                        <span><b>File Manager</b></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="change-password.php">
                                    <div class="icona">
                                        <i class="fa fa-folder" aria-hidden="true" style="font-size:15px;"></i>
                                        <i class="fa fa-folder" aria-hidden="true" style="font-size:15px;"></i>
                                    </div>
                                    <div class="namea">
                                        <span><b>Change Password</b></span><br>
                                        <span><b>Chnage Password</b></span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Sidebar -->
                         <!-- Add or edit data model here  -->
                <div id="view_salary" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Enter The Password to View Salary</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input class="form-control" name="password" id="password" type="password">                                                                                
                                </div>

                                <div class="submit-section">
                                    <!-- leave_id passed here's -->
                                    <input class="btn btn-primary submit-btn" onclick="salary_auth()" type="submit" name="login" value="Submit" id="login" >                                                                            
                                </div>
                                <div class="text-center">
                                    <a href=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                    
                    <!-- Frame Modal Bottom -->
    <div id="announcement" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Important Notice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                  <div class="modal-body">
                    <div class="row d-flex justify-content-center align-items-center">
                      <p class="pt-3 pr-2">Salary report is now password protected. You can view your salary report with your <b>login</b> password.
                      </p>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!--          <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
<!-- Frame Modal Bottom -->
                    
                    <input type="hidden" value="<?php echo $birth_status; ?>" id="birth_status" name="birth_status" >
                    <input type="hidden" value="<?php echo $_SESSION['e_id']; ?>" id="e_id" name="e_id" >
                    <input type="hidden" value="<?php echo $_SESSION['admin_id']; ?>" id="admin_id" name="admin_id" >
                    <input type="hidden" value="<?php echo $e['emp_cardid']; ?>" id="card_id" name="card_id" >
                    <input type="hidden" value="<?php echo $shift; ?>" id="shiftid" name="shiftid" >
                   