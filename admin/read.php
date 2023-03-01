<?
if(!isset($_POST['action'])){
    header("./index.php");
    exit();
}
ob_start();
session_start();
require_once ("../dbconfig.php");
require_once ("api/index.php");
include_once '../stripe/model/Stripeupdate.php';
die;
// Fetch Employee Data
if($_POST['action'] == 'e_view')
{
    $sql = mysqli_query($conn, "Select * from employee where e_id = ".$_POST['e_id']."");
    $row = mysqli_fetch_assoc($sql);
    $row['join_date'] = date("Y-m-d", $row['join_date']);
    echo json_encode($row);
}
// Fetch department Data
if($_POST['action'] == 'department_fetch')
{
    $sql = mysqli_query($conn, "Select * from departments where departments_id = ".$_POST['d_id']."");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}
// Fetch designation Data
if($_POST['action'] == 'fetch_designation')
{
    $sql = mysqli_query($conn, "Select * from designation where designation_id = ".$_POST['d_id']."");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// Fetch designation Data
if($_POST['action'] == 'view_user')
{
    $sql = mysqli_query($conn, "Select * from add_users where user_id = ".$_POST['user_id']."");
    $row = mysqli_fetch_assoc($sql);
    $row['user_add_date'] = date("Y-m-d", $row['user_add_date']);
    echo json_encode($row);
}
// view salary from edit
if($_POST['action'] == 'view_edit_salary')
{
      $password = hash("sha1", $_POST['pass']);
 
    $sql = mysqli_query($conn, "Select * from company_admin where admin_id = ".(int)$_SESSION['admin_id']."");
    $row = mysqli_fetch_assoc($sql);
    $no = mysqli_num_rows($sql);
    
    if ($no > 0) {
        $pass = $row['admin_password'];
        if ($pass == $password) {
            $res = [true, $row];
        } else {
             $res = [false, $row];
        }
    } else {
         $res = [false, $row];
    }
    echo json_encode($res);
}



// Fetch designation Fetch
if($_POST['action'] == 'designation_data')
{
    $sql = mysqli_query($conn, "SELECT * FROM designation INNER JOIN departments on designation.department_id = departments.departments_id  where designation.admin_id = " . $_POST['admin_id'] . " ");
    $no = 1;
    $output = '<table id="designation_data" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Designation </th>
                            <th>Department </th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
    while($row = mysqli_fetch_assoc($sql))
    {
        $output .=' <tr>
                        <td>'.$no++.'</td>
                        <td>'.$row['designation_name'].'</td>
                        <td>'.$row['departments_name'].'</td>
                        <td class="text-right">
                            <a class="edit" href="#" id="'.$row['designation_id'].'" style="color:black" title="Edit" data-toggle="modal" data-target="#edit_designation"><i class="fa fa-pencil m-r-5"></i></a>';
//                        if($row['d_used_count'] > 0) {
//                            $output .=' <a href="#"  title="This Data is Used in Other Place You Can not Delete This."  style="color:black;cursor: not-allowed"><i class="fa fa-trash-o m-r-5"></i></a>';
//                        } else{
        $output .='<a class="delete" href="#" id="'.$row['designation_id'].'" title="Delete" data-toggle="modal" data-target="#delete_designation" style="color:black"><i class="fa fa-trash-o m-r-5"></i></a>';
//                        }
        $output .='     <td>
                    <tr>';
    }
    $output .='</tbody>
            </table>';
    echo $output;
}

// salary_fetch
if ($_POST['action'] == 'salary_view') {
    $sql = mysqli_query($conn, "Select * from staff_salary where salary_id = " . $_POST['s_id'] . "");
    $row = mysqli_fetch_assoc($sql);
    $output = '<div class="row">
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Employee Name<span class="text-danger"></span></label>
                                <input class="form-control" value="'.$row['emp_name'].'" type="text" id="employee" name="employee" disabled="">
                            </div>
                        </div>
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Working Days<span class="text-danger"></span></label>
                                <input class="form-control" value="'.$row['working_days'].'" type="number" id="working_days" name="workig_days" disabled="">
                            </div>
                        </div>
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Present Days<span class="text-danger"></span></label>
                                <input class="form-control" value="'.$row['present_days'].'" type="number" id="present_days" onchange="countAbsentDays(this.value);" name="present_days" >
                            </div>
                        </div>
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Absent Days<span class="text-danger"></span></label>
                                <input class="form-control" value="'.$row['absent_days'].'" type="number" id="absent_days" name="absent_days" disabled="" >
                                <input class="form-control" type="hidden" disabled id="empId" value ="'.$row['e_id'].'" name="empId" >
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-sm-6"> 
                            <h4 class="text-primary">Earnings</h4>
                            <div class="form-group">
                                <label>Basic Salary</label>
                                <div class="input-container">
                                    <i class="fa fa-rupee icon"></i>
                                    <input class="form-control" type="text" id="ubasic" disabled value="'.$row['basic'].'" name="ubasic" placeholder="Enter Amount" >
                                    <input  type="hidden" id="s_id" value="'.$row['salary_id'].'" name="s_id">
                                </div>    
                            </div>';
    if($row['da'] == 'not used'){
        $output .='<div class="form-group">
                                                <input class="form-control" type="hidden" disabled id="uda" value ="'.$row['da'].'" name="uda" >
                                           </div>';
    }else{
        $output .='<div class="form-group">
                                                DA
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" disabled id="uda" value ="'.$row['da'].'" name="uda" >
                                                </div>    
                                            </div>';
    }
    if($row['hra'] == 'not used') {
        $output .= '<div class="form-group">
                                                <input class="form-control" type="hidden" value="'.$row['hra'].'" id="uhra" name="uhra" >
                                            </div>';
    }else{
        $output .= '<div class="form-group">
                                HRA
                                <div class="input-container">
                                    <i class="fa fa-rupee icon"></i>
                                    <input class="form-control" type="text" id="uhra" disabled value="'.$row['hra'].'" name="uhra" >
                                </div>
                            </div>';
    }
    if($row['conveyance'] == 'not used') {
        $output .= '<div class="form-group">
                                                    <input class="form-control" type="hidden" id="uconveyance" value="'.$row['conveyance'].'" name="uconveyance" >
                                                </div>';
    }else{
        $output .= '<div class="form-group">
                                            Conveyance
                                            <div class="input-container">
                                                <i class="fa fa-rupee icon"></i>
                                                <input class="form-control" type="text" disabled id="uconveyance" value="'.$row['conveyance'].'" name="uconveyance" >
                                            </div>
                                        </div>';
    }
    if($row['allowance'] == 'not used') {
        $output .= '<div class="form-group">
                                                <input class="form-control" type="hidden" id="uallow" value="not used" name="uallow" >
                                            </div>';
    }else{
        $output .='<div class="form-group">
                                                Allowance
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" disabled id="uallow" value ="'.$row['allowance'].'" name="uallow" >
                                                </div>    
                                            </div>';
    }
    if($row['medical_allowance'] == 'not used') {
        $output .= '<div class="form-group">
                                                    <input class="form-control" type="hidden" id="um_allow" value="not used" name="um_allow" style="padding-left: 12px;" placeholder="Enter Only Rupees">
                                                </div>';
    }else{
        $output .='<div class="form-group">
                                                Medical Allowance
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" disabled id="um_allow" value ="'.$row['medical_allowance'].'" name="um_allow" >
                                                </div>    
                                            </div>';
    }
    $output .= '<div class="form-group">
                                            Over Time
                                             <div class="input-container">
                                                <i class="fa fa-rupee icon"></i>
                                                <input class="form-control" type="text" id="over_time" value="'.$row['over_time'].'" name="over_time" style="padding-left: 12px;" placeholder="Enter Only Rupees">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            Incentive
                                             <div class="input-container">
                                                <i class="fa fa-rupee icon"></i>
                                                <input class="form-control" type="text"  id="incentive" value="'.$row['incentive'].'" name="um_allow" style="padding-left: 12px;" placeholder="Enter Only Rupees">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">  
                                        <h4 class="text-primary">Deduction</h4>';
    if($row['tds'] == 'not used') {
        $output .= '<div class="form-group">
                                               <input class="form-control" type="hidden" id="utds" value="not used" name="utds" >
                                            </div>';
    }else{
        $output .='<div class="form-group">
                                                        TDS
                                                        <div class="input-container">
                                                            <i class="fa fa-rupee icon"></i>
                                                            <input class="form-control" type="text" disabled id="utds" value ="'.$row['tds'].'" name="utds" >
                                                        </div>    
                                                    </div>';
    }
    if($row['esi'] == 'not used') {
        $output .= '<div class="form-group">
                                                    <input class="form-control" type="hidden" value="not used" id="uesi" name="uesi" >
                                                </div>';
    }else{
        $output .='<div class="form-group">
                                                ESI 
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" disabled id="uesi" value ="'.$row['esi'].'" name="uesi" >
                                                </div>    
                                            </div>';
    }
    if($row['pf'] == 'not used') {
        $output .= '<div class="form-group">
                                                       <input class="form-control" type="hidden" value="not used" id="upf" name="upf" >
                                                    </div>';
    }else{
        $output .='<div class="form-group">
                                                PF 
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text"  id="upf" value ="'.$row['pf'].'" name="upfpf" >
                                                </div>    
                                            </div>';
    }
    
    if($row['prof_tax'] == 'not used') {
        $output .= '<div class="form-group">
                                                    <input class="form-control" type="hidden"  id="uproftax" value="not used" name="uproftax" >
                                                </div>';
    }else{
        $output .='<div class="form-group">
                                                Prof. Tax
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" disabled id="uproftax" value ="'.$row['prof_tax'].'" name="uproftax" >
                                                </div>    
                                            </div>';
    }
    if($row['labour_welfare'] == 'not used') {
        $output .= '<div class="form-group">
                                                        <input class="form-control" type="hidden"  value="not used" id="ul_wel" name="ul_wel" >
                                                    </div>';
    }else{
        $output .='<div class="form-group">
                                                Labour Welfare
                                                <div class="input-container">
                                                    <i class="fa fa-rupee icon"></i>
                                                    <input class="form-control" type="text" id="ul_wel" disabled value ="'.$row['labour_welfare'].'" name="ul_wel" >
                                                </div>    
                                            </div>';
    }
    
    $output .= '<div class="form-group">
                                                        Leave
                                                        <div class="input-container">
                                                            <i class="fa fa-rupee icon"></i>
                                                            <input class="form-control" type="text" value="'.$row['e_leave'].'"   id="ue_leave" name="ue_leave" >
                                                        </div>
                                                    </div>
                                                   <div class="form-group">
                                                        Break Violation
                                                        <div class="input-container">
                                                            <i class="fa fa-rupee icon"></i>
                                                            <input class="form-control" type="text" id="break_violation" value="'.$row['break_violation'].'" name="break_violation" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        Late Violation
                                                        <div class="input-container">
                                                            <i class="fa fa-rupee icon"></i>
                                                            <input class="form-control" type="text" id="late_fine" value="'.$row['late_fine'].'" name="late_fine" >
                                                        </div>
                                                    </div>';

    $output .= '</div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn" onclick="update_salary()" name="submit" id="submit">Submit</button>
                                </div>
                            </div>';
    echo $output;
}

// Fetch Holiday
if($_POST['action'] == 'h_view')
{
    $sql = mysqli_query($conn, "Select * from holidays where holiday_id = '".$_POST['holiday_id']."' ");
    $row = mysqli_fetch_assoc($sql);
    $row['holiday_date'] =date("Y-m-d",$row['holiday_date']);
    echo json_encode($row);
}

// break_fetch
if ($_POST['action'] == 'fetch_break') {
    $sql = mysqli_query($conn, "Select * from break where b_id = " . $_POST['id'] . "");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// fetch policies
if ($_POST['action'] == 'p_view') {
    $sql = mysqli_query($conn, "Select * from company_policy inner join departments on departments.departments_id = company_policy.policy_department where policy_id = ".$_POST['p_id']." ");
    $row = mysqli_fetch_array($sql);
    echo json_encode($row);
}

// Email Check
if ($_POST['action'] == 'email_check') {
    $email = $_POST['email'];
    $query = mysqli_query($conn, "select *  from employee where e_email LIKE  '%$email%' ");
    $no = mysqli_num_rows($query);

    if ($no > 0) {
        echo "yes";

    }else{
        echo "no";
    }

}

// Fetch announcement Data
if($_POST['action'] == 'fetch_attendance')
{
    $sql = mysqli_query($conn, "Select * from attendance where Attandance_id =". $_POST['atr_id']);
    $row = mysqli_fetch_assoc($sql);
    $row['in_time'] = date("d/m/Y h:i:s A",$row['in_time']);
    echo json_encode($row);
}

// Fetch announcement Data
if($_POST['action'] == 'announcement_fetch')
{
    $sql = mysqli_query($conn, "Select * from notice where n_id = ".$_POST['n_id']." ");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// change_show
if ($_POST['action'] == 'fetch_announcement') {
    $admin_id= $_POST['admin_id'];
 
        $table = '<table id="announcement" class="table table-striped custom-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 30px;"><b>No</b></th>
                            <th class="text-center"><b>Announcement Subject</b></th>
                            <th class="text-center"><b>Announcement</b></th>
                            <th class="text-center"><b>Status</b></th>
                            <th class="text-right" ><b>Action</b></th>
                        </tr>
                    </thead>
                    <tbody id="view_data">';
                        
                        $sql = mysqli_query($conn, "SELECT * FROM notice where admin_id = " . $admin_id . " ");
                        $no = 1;
                        $output = '';
                        while ($row = mysqli_fetch_array($sql)) {
                            if ($row['show_status'] == 0) { 
                                $status = '<i class="fa fa-dot-circle-o text-danger"></i> Hide';
                            } else { 
                                $status = '<i class="fa fa-dot-circle-o text-success"></i> Visible';
                            }
                            
                            $table .='<tr>
                                <td>'.$no++.'</td>
                                <td class="text-center">'.$row['notice_subject'].'</td>
                                <td class="text-center">'.$row['notice'].'</td>
                                <td class="text-center">
                                    <div class="menu-right">
                                        '.$status.'
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">';
                                        if ($row['show_status'] == 0) { 
                                            $table .= '<a class="dropdown-item show_data" href="#" id="'.$row['n_id'].'" style="color:green" title="Visible"><i class="fa fa-check-circle"></i> Visible</a>';
                                        }else{
                                            $table .= '<a class="dropdown-item hide_data" href="#" id="'.$row['n_id'].'" style="color:red" title="Hide"><i class="fa fa-times-circle"></i> Hide</a>';
                                        }
                                        $table .= '<a class="dropdown-item edit_data" href="#" id="'.$row['n_id'].'" style="color:black" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item delete_data" href="#" id="'.$row['n_id'].'" style="color:black" title="Delete"><i class="fa fa-trash m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                        }
                    $table .='</tbody>
                </table>';
    echo $table;
}


// Show Attendance Info
if ($_POST['action'] == 'attendance_info')
{
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = ".$_SESSION['shift_id']." ");
    while($ron = mysqli_fetch_array($query))
    {
        $timezone = $ron['timezone'];
    }
    date_default_timezone_set("$timezone");
    $day = $_POST['day'];
    $fromdt= strtotime("$day 00:00:00");
    $todt= strtotime("$day 23:59:59");
  //  $sql = "SELECT * FROM `attendance` WHERE admin_id = ".$_POST['admin_id']." and in_time between $fromdt and $todt ";
    $sql = "SELECT * FROM attendance INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " and attendance.in_time between $fromdt and $todt  ";
    $result = mysqli_query($conn,$sql);
    $output =' <table id="attendance" class="table table-striped custom-table table-nowrap mb-0">
                <thead>
                    <tr>
                        <th><b>Employee Name</b></th>
                        <th><b>Employee Card No</b></th>
                        <th><b>Punch In</b></th>
                        <th><b>Status</b></th>
                        <th><b>Device Type</b></th>
                        <th><b>Fine</b></th>
                        <th class="text-right" ><b>Action</b></th>
                    </tr>
                </thead>
                <tbody>';
    while($row = mysqli_fetch_array($result)){
        $emp_name = $row['emp_name'];
        $emp_no = $row['employee_cardno'];
        $punch_intime = $row['in_time'];

        if($row['present_status'] == 'Late')
        {
            $status =  "<span style='color:red' >Late</span>";
        }
        else
        {
            $status =  "<span style='color:green' >On Time</span>";
        }
        if($row['fine'] == '')
        {
            $fine =  "<span style='color:green' >0</span>";
        }
        else
        {
            $fine =  "<span style='color:red' >".$row['fine']."</span>";
        }
        $device = $row['devicetype'];
        if($device == 75){
            $device = '<img src="app/img/face-recognition.svg" alt="Face Recognition" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Face Recognition"></img>';
        }elseif ($device == 38){
            $device = '<img src="app/img/fingerprint.svg" alt="Finger Print" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Finger Print">';
        }else{
            $device = '<img src="app/img/id-card.svg" alt="ID Card" width="20" height="20" data-toggle="tooltip" data-placement="top" title="ID Card"></img>';
        }

        date_default_timezone_set('Asia/Kolkata');
        $output .='<tr>
                        <td>'.$row['emp_name'].'</td>
                        <td>'.$emp_no.'</td>
                        <td>'.date("d m Y h:i:sa", $punch_intime).'</td>
                        <td><b>'.$status.'</b></td>
                        <td><center>'.$device.'</center></td>
                        <td><b>'.$fine.'</b></td>
                        <td class="text-right">
                            <a class="edit" href="#"  id="'.$row['Attandance_id'].'" style="color:black" title="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                            <a class="delete" href="#"  id="'.$row['Attandance_id'].'" style="color:black" title="Delete" ><i class="fa fa-trash-o m-r-5"></i></a>
                        </td>
                    </tr>';
    }
    $output .='</tbody>
        </table>';
    echo $output;
}

// Employee Document View
if ($_POST['action'] == 'view_doc')
{
    $sql = mysqli_query($conn, "select * from employee_document where e_id = '" . $_POST['e_id'] . "'");
    $output = '';
    $no = 0;
    while ($row = mysqli_fetch_array($sql)) {
        if ($row['file_extension'] == 'pdf') {
            $status = '<div class="card-file-thumb">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>';
        } else {
            $status = '<div class="card-file-thumb-png">
                                        <img src="../employee/uploads/' . $row['file_name'] . '"  height="120px" width="100%">      
                                    </div>';
        }

        $output .= '<a href="../employee/uploads/' . $row['file_name'] . '" target="_blank" id="'.$row['document_id'].'" class="download"><div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3"> <div class="card card-file">' . $status . '<div class="card-body">
                                    <h6>' . $row['file_name'] . '</a></h6>
                                    <span>' . $row['file_size'] / 1000 . ' KB</span><a href="download.php?file='.$row['file_name'].'" class="download" style="text-align: right;margin-left: 50%;color: black;"><i class="fa fa-download" aria-hidden="true"></i></a>
                                </div>
                                </div>
                                </div></a>';
        $no++;
    }
    if($no == 0){
        $output ='<div class="text-center"><h4>Employee does not upload any documents</h4></div>';
    }
    echo $output;
}

// Fetch Working Days
if($_POST['action'] == 'view_working_days')
{
    $sql = mysqli_query($conn, "Select * from working_days where admin_id = ".$_POST['admin_id']."");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// Fetch Remaining Leaves
if($_POST['action'] == 'remaining_leave')
{
    $sql = mysqli_query($conn, "Select * from total_add_leave where e_id = ".$_POST['r_id']."");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// Fetch Leave Type
if($_POST['action'] == 'leave_fetch')
{
    $sql = mysqli_query($conn, "Select * from add_leave where leave_id = ".$_POST['leave_id']."");
    $row = mysqli_fetch_assoc($sql);
    echo json_encode($row);
}

// see who is absent today
if($_POST['action'] == 'absent_view')
{
    if(!isset($_SESSION)) { session_start(); }
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = " . $_SESSION['shift_id'] . " ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
        $break_fine = $ron['break_fine'];
        $late_fine = $ron['late_fine'];
    }

    date_default_timezone_set($timezone);

    $s_date = date("Y-m-d 00:00:00");
    $e_date = date("Y-m-d 23:59:59");
    $start_date = strtotime($s_date);
    $end_date = strtotime($e_date);
    $atten = mysqli_query($conn, "SELECT * FROM `attendance` INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND in_time between $start_date and $end_date ");
    $empl = mysqli_query($conn, "SELECT * FROM `employee` WHERE admin_id = " . $_POST['admin_id'] . " and shift_no = " . $_SESSION['shift_id'] . " and employee_status = 1 and delete_status = 0 ");

    $arr_id = array();
    $output_present = '<table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Punch time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
    $count_p = 0;
    while ($row = mysqli_fetch_array($atten)) {
        $arr_id[] = $row['employee_id'];
        $count_p++;
        $output_present .= '<tr>
                        <td>'.$count_p.'</td>
                        <td>'.$row['emp_name'].'</td>
                        <td>'.date("d-m-Y h:i:sa",$row['in_time']).'</td>
                        <td><span class="present-status">Present</span></td>
                    </tr>';
    }

    $output_present .='</tbody>
                   </table>';

    $arr = array();
    $arr_name = array();

    while ($ro = mysqli_fetch_array($empl)) {
        $arr[] = $ro['e_id'];
        $arr_name[$ro['e_id']] = $ro['e_firstname']." ".$ro['e_lastname'];
    }
    $result=array_values(array_diff($arr,$arr_id));

    $output = '<table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

    $count = 0;
    for($i = 0; $i < sizeof($result) ; $i++){
        $count++;
        $output .= '
                    <tr>
                        <td>'.$count.'</td>
                        <td>'.$arr_name[$result[$i]].'</td>
                        <td><span class="absent-status">Absent</span></td>
                    </tr>';
    }
    if($count == 0){
        $output .='<tr><td></td><td><center>No one Absent Today.</center></td></tr>';
    }

    $output .= '</tbody>
            </table>';


    $arr = array();
    $arr[0] = $output;
    $arr[1] = $output_present;
    $arr[2] = "<span style='font-size: 22px;color: #0ba408'>P-</span>".$count_p. " <span style='font-size:22px;color: #ff0034'>A-</span>" .sizeof($result);
    echo json_encode($arr);
}

// see who is late
if($_POST['action'] == 'late_view')
{
    if(!isset($_SESSION)) { session_start(); }
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = " . $_SESSION['shift_id'] . " ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set($timezone);
    $company_start = strtotime(date("Y-m-d $company_time:00"));
    $s_date = $_POST['from_date'];
    $e_date = $_POST['to_date'];
    $start_date = strtotime("$s_date 00:00:00");
    $end_date = strtotime("$e_date 23:59:59");
    if($_POST['e_id'] == ""){
        $atten = mysqli_query($conn, "SELECT * FROM `attendance` INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND in_time between '$start_date' and '$end_date' ");
    }else{
        $atten = mysqli_query($conn, "SELECT * FROM `attendance` INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE employee_id = " . $_POST['e_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND in_time between '$start_date' and '$end_date' ");
    }

    $output_late = '<table id="late_data" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Punch Time</th>
                                    <th>Late Time</th>
                                    <th>Late Fine</th>
                                </tr>
                            </thead>
                            <tbody>';
    $count_p = 0;
    while ($row = mysqli_fetch_array($atten)) {

        $company_start = date("d/m/Y $company_time:00",$row['in_time']);
        $ch_date = strtotime($company_start);

        if($row['present_status'] == 'Late'){

            $output_late .= '<tr>
                        <td>'.++$count_p.'</td>
                        <td>'.$row['emp_name'].'</td>
                        <td><span style="color:red">'.date("d/m/Y h:i:sa",$row['in_time']).'</span></td>
                        <td><span style="color:red">'.$row['late_time'].' Min</span></td>
                        <td><span style="color:red">'.$row['fine'].' Rupees</span></td>
                    </tr>';
        }
    }
    if($count_p == 0){
        $output_late .='<tr><td></td><td><center>No One is Late Today.</center></td></tr>';
    }
    $output_late .='</tbody>
                   </table>';
    echo $output_late;
}

// see email exists check
if($_POST['action'] == 'emailexist'){
    $email = $_POST['email'];
    $event = new Util();
    if($event->emailExists($email,$conn)){
        echo json_encode(array('status' => 'new'));
    }else{
        echo json_encode(array('status' => 'used'));
    }
    exit();
}

// see Attendance Information
if($_POST['action'] == 'attendance_fetch')
{
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $admin_id = $_POST['admin_id'];
    $id = $_POST['id'];
    $query = mysqli_query($conn, "select *  from company_time where admin_id = '$admin_id' ");
    $ron = mysqli_fetch_array($query);
    $timezone = $ron['timezone'];


    date_default_timezone_set($timezone);
    $atten = mysqli_query($conn, "SELECT * FROM `attendance` WHERE Attandance_id = '$id' ");

    date_default_timezone_set("Asia/Kolkata");
    $row = mysqli_fetch_array($atten);

    $date = date("d M Y", $row['in_time']);
    $punch_time = date("d M Y h:i:s A", $row['in_time']);
    $s_date = date("d-m-Y",$row['in_time']);
    $e_date = date("d-m-Y",$row['in_time']);
    $start_date = strtotime("$s_date 00:00:00");
    $end_date = strtotime("$e_date 23:59:59");
    $break_fetch = mysqli_query($conn, "SELECT * FROM `break` WHERE employee_id = " . $row['employee_id'] . "  and break_time between '$start_date' and '$end_date' ");
    $diff = 0;
    $break_data ="";
    $count = 0;
    while ($break = mysqli_fetch_array($break_fetch))
    {
        if($break['out_time'] != 'OUT'){
            $count++;
            $break_data .='<li>
                                    <p class="mb-0">Punch In at</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        '.date("h:i:s A",$break['break_time']).'
                                    </p>
                                </li>
                                <li>
                                    <p class="mb-0">Punch Out at</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        '.date("h:i:s A",$break['out_time']).'
                                    </p>
                              </li>';
            $diff += $break['out_time'] - $break['break_time'];
        }
    }
    if($count == 0){
        $break_data ='<li>
                              <p class="text-center">
                                 Break Details Not Available
                              </p>
                          </li>';
    }
    $break = round($diff/60);
    $break_time = '<div class="col-md-12 col-6 text-center" >
                            <div class="stats-box">
                                <p>Break</p>
                                <h6>'.$break.'</h6>
                                <input type="hidden" value="'.$id.'" id="at_id" name="at_id">
                            </div>
                        </div>';
    $punch_time ='<h6>Punch In at</h6>
                        <p>'.$punch_time.'</p>';
    $arr = array();
    $arr[0]= $punch_time;
    $arr[1]= $break_data;
    $arr[2]= $break_time;
    $arr[3]= $date;
    echo json_encode($arr);
}

// see break_violation
if($_POST['action'] == 'break_violation')
{
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where admin_id = " . $_POST['admin_id'] . " ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set($timezone);
    $s_date = $_POST['from_date'];
    $e_date = $_POST['to_date'];
    $start_date = strtotime("$s_date 00:00:00");
    $end_date = strtotime("$e_date 23:59:59");
    if($_POST['e_id'] == "All"){
        $atten = mysqli_query($conn, "SELECT * FROM `break` WHERE admin_id = " . $_SESSION['admin_id'] . "  and break_time between '$start_date' and '$end_date' and violation = 'Yes' ");
    }else{
        $atten = mysqli_query($conn, "SELECT * FROM `break` WHERE employee_id = " . $_POST['e_id'] . "  and break_time between '$start_date' and '$end_date' and violation = 'Yes' ");
    }

    $output_violation = '<table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Break Start Time</th>
                                    <th>Break End Time</th>
                                    <th>Total Break Time</th>
                                    <th>Violation Fine</th>
                                </tr>
                            </thead>
                            <tbody>';
    $no = 0;
    while ($row = mysqli_fetch_array($atten)) {
        $name = $row['emp_name'];
        $break_time = $row['break_time'];
        $break_out = $row['out_time'];
        $no++;
        $output_violation .='<tr>
                                    <td>'.$no.'</td>
                                    <td>'.$name.'</td>
                                    <td>'.date("d/m/Y h:i:sa", $break_time).'</td>
                                    <td>'.date("d/m/Y h:i:sa", $break_out).'</td>
                                    <td><span style="color: red">'.$row['total_time'].' Min</span></td>
                                    <td><span style="color: red">'.$row['fine'].' Rupess</span></td>
                                </tr>';
    }


    $output_violation .='</tbody>
                   </table>';
    if($no == 0){
        $output_violation ='<span class="text-blue">No Any Violation</span>';
    }

    echo $output_violation;
}

// See Total Employee in Break
if($_POST['action'] == 'break_out')
{
    if(!isset($_SESSION)) { session_start(); }
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = " . $_SESSION['shift_id'] . " ");
    while ($r = mysqli_fetch_array($query)) {
        $b_time = $r['company_break_time'];
        $timezone = $r['timezone'];
        $fine = $r['break_fine'];
    }
    date_default_timezone_set($timezone);
    $fromdt = strtotime(date("Y-m-d 00:00:00"));
    $todt = strtotime(date("Y-m-d 23:59:59"));

    $query_break = mysqli_query($conn, "SELECT * FROM break INNER JOIN employee ON employee.e_id = break.employee_id WHERE break.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND break.break_time between $fromdt and $todt ");
    $count = 0;
    $no = 0;
    $output = '<table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Break Start Time</th>
                            <th>Break Time Left</th>
                        </tr>
                    </thead>
                    <tbody>';
    $output_violation = '<table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Break Start Time</th>
                            <th>Break End Time</th>
                            <th>Total Break Time</th>
                            <th>Violation Fine</th>
                        </tr>
                    </thead>
                    <tbody>';
    while($countemp = mysqli_fetch_array($query_break)){
        $name = $countemp['emp_name'];
        $break_time = $countemp['break_time'];
        $time = time();
        $break_out = $countemp['out_time'];
        if($break_out == 'OUT'){
            $count++;
            $break_end = $break_time + 1740;
            $min = $break_end - $time;
            $diff = ceil($min/60)." min";
            if($min < -1){
                $diff = '<span style="color:red">Late</span>';
            }
            $output .='<tr>
                            <td>'.$count.'</td>
                            <td>'.$name.'</td>
                            <td>'.date("d/m/Y h:i:sa", $break_time).'</td>
                            <td>'.$diff.'</td>
                        </tr>';
        }
        if($countemp['violation'] == 'Yes'){
            $no++;
            $violation = 0;
            $diff = $break_out - $break_time;
            $min = ceil($diff/60);
            $total_break = $min;
            $violation = $total_break - 30;
            $fine = $countemp['fine'];

            $output_violation .='<tr>
                                    <td>'.$no.'</td>
                                    <td>'.$name.'</td>
                                    <td>'.date("d/m/Y h:i:sa", $break_time).'</td>
                                    <td>'.date("d/m/Y h:i:sa", $break_out).'</td>
                                    <td>'.$min.' min</td>
                                    <td>'.$fine.'</td>
                                </tr>';
        }
    }

    $output .= '</tbody>
            </table>';
    if($count == 0){
        $output ='<center><span style="color: darkblue">No one in Break</span></center>';
    }
    $output_violation .= '</tbody>
            </table>';
    if($no == 0){
        $output_violation ='<center><span style="color: darkblue">No any Break Violation Today</span></center>';
    }
    $arr = array();
    $arr[0] = $output;
    $arr[1] = $count;
    $arr[2] = $output_violation;
    $arr[3] = $no;
    echo json_encode($arr);
}

// See Total Employee is Late Today
if($_POST['action'] == 'late_employee')
{
    if(!isset($_SESSION)) { session_start(); }
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = " . $_SESSION['shift_id'] . " ");
    while ($ron = mysqli_fetch_array($query)) {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }

    date_default_timezone_set($timezone);
    $c_date = date("Y-m-d $company_time");
    $date = strtotime($c_date);
    $sql = mysqli_query($conn, "SELECT * FROM `attendance` INNER JOIN employee ON employee.e_id = attendance.employee_id WHERE attendance.admin_id = " . $_POST['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND attendance.in_time > '$date' ");
    $output = '<table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Punch Time</th>
                            <th>Late Time</th>
                            <th>Late Fine</th>
                        </tr>
                    </thead>
                    <tbody>';
    $count = 0;
    while($row = mysqli_fetch_array($sql)){
        $count++;
        $output .='<tr>
                        <td>'.$count.'</td>
                        <td>'.$row['emp_name'].'</td>
                        <td>'.date("d/m/Y h:i:sa", $row['in_time']).'</td>
                        <td>'.$row['late_time'].'</td>
                        <td><span style="color:red">'.$row['fine'].'</span></td>
                    </tr>';
    }
    if($count == 0){
        $output .='<tr><td></td><td><center>No one is Late Today.</center></td></tr>';
    }
    $output .= '</tbody>
            </table>';
    $arr = array();
    $arr[0] = $output;
    $arr[1] = $count;
    echo json_encode($arr);
}

// salary_authtication
if($_POST['action'] == 'salary_auth')
{
    $user_id = $_SESSION['admin_id'];
    $password = hash('sha1',$_POST['password']);
    $sql  = "Select * from accounting_auth where admin_id = '$user_id' and  password = '$password' ";
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        $_SESSION['accounting'] = 'yes';
        $status = array();
        $status['status'] ='valid';
        echo json_encode($status);
    }else{
        $status = array();
        $status['status'] ='invalid';
        echo json_encode($status);
    }
}

// Fetch Break Detalis
if($_POST['action'] == 'break_info')
{
    $event = new Util();
    $res = $event->refreshPagedata($conn, $_SESSION['devIndex']);
    $query = mysqli_query($conn, "select *  from company_time where time_id = ".$_SESSION['shift_id']." ");
    while($ron = mysqli_fetch_array($query))
    {
        $break_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
        $fine = $ron['break_fine'];
    }

    date_default_timezone_set("$timezone");
    $day = $_POST['day'];

    $fromdt= strtotime("$day 00:00:00");
    $todt= strtotime("$day 23:59:59");

    //$sql = "SELECT * FROM `break` WHERE admin_id = ".$_POST['admin_id']." and break_time between $fromdt and $todt ";
    $sql = "SELECT * FROM `break` INNER JOIN employee ON employee.e_id = break.employee_id WHERE  break.admin_id = " . $_SESSION['admin_id'] . " AND employee.shift_no = " . $_SESSION['shift_id'] . " AND break.break_time BETWEEN $fromdt and $todt";
    // echo $sql;
    $result = mysqli_query($conn,$sql);
    $output ='<table id="attendance" class="table table-striped custom-table table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Break Start</th>
                                <th>Break End</th>
                                <th>Device Type</th>
                                <th>Total Break Time</th>
                                <th>Violation</th>
                                <th>Violation Fine</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

    while($row = mysqli_fetch_array($result)){
        $emp_id = $row['employee_id'];
        $emp_name = $row['emp_name'];
        $emp_no = $row['employee_cardno'];
        $break_intime = $row['break_time'];
        $status = $row['violation'];
        $fine = $row['fine'];
        $device = $row['devicetype'];
        if($device == 75){
            $device = '<img src="app/img/face-recognition.svg" alt="Face Recognition" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Face Recognition"></img>';
        }elseif ($device == 38){
            $device = '<img src="app/img/fingerprint.svg" alt="Finger Print" width="20" height="20" data-toggle="tooltip" data-placement="top" title="Finger Print">';
        }else{
            $device = '<img src="app/img/id-card.svg" alt="ID Card" width="20" height="20" data-toggle="tooltip" data-placement="top" title="ID Card"></img>';
        }
        if($row['out_time'] == 'OUT'){
            $break_outtime = '<span style="color:red">OUT</span>';
            $in_status = 0;
        }else{
            $diff = $row['out_time'] - $break_intime;
            $break_time = ceil($diff/60);
            // $in_status = $row['total_time'];
            $in_status = $break_time;

            $break_outtime = date("h:i:s a", $row['out_time']);
        }
        $comment = $row['comment'];
        if($status == 'Yes')
        {
            $status = '<span style="color:red">Yes</span>';
        }
        else{
            $status = '<span style="color:green">No</span>';
        }
//  <td><center>'.$emp_no.'</center></td>
        $output .='         <tr data-id="row-'.$row['b_id'].'">
                                <td data-id="row-'.$row['b_id'].'" style="overflow:hidden !important; ">'. $emp_name = $row['emp_name'].'</td>
                              
                                <td>'.date("h:i:s a", $break_intime).'</td>
                                <td>'.$break_outtime.'</td>
                                <td><center>'.$device.'</center></td>
                                <td>'.$in_status.'</td>
                                <td>'.$status.'</td>
                                <td>'.$fine.'</td>
                                <td>'.$comment.'</td>
                                <td>
                                    <a class="edit" href="#" id="'.$row['b_id'].'" style="color:black" title="Edit" ><i class="fa fa-pencil"></i></a>
                                    <a class="delete" href="#" id="'.$row['b_id'].'" style="color:black" title="Delete" ><i class="fa fa-trash-o m-r-5"></i></a>
                                </td>
                            </tr>
                        ';
    }
    $output .= '</tbody>
                    </table>';
    echo $output;
}
// View Invoice Logo
if($_POST['action'] == 'view_invoice_logo')
{
    $sql = mysqli_query($conn," select * from company_logo where admin_id = '".$_POST['admin_id']."' ");
    $output = '';
    while ($row = mysqli_fetch_array($sql))
    {
        $output .= '<img src="company_document/'.$row['invoice_logo'].'" width="80px" height="40px">
            <input type="hidden" id="invoice_id" value="'.$row['logo_id'].'">
                ';
    }
    echo $output;
}

// View Estimate Plan Deatails
if ($_POST['action'] == 'view_plan') {
    $id = $_POST['plan_name'];
    $month = $_POST['month'];
    $admin_id = $_POST['admin_id'];
//    $no_of_emp = $_POST['current_employee'];
    $no_of_emp = $_POST['add_employee'];
    $query = mysqli_query($conn, "SELECT * FROM product_plan where plan_name = '$id'");
    while ($row = mysqli_fetch_array($query)) {
        $plan_name = $row['plan_name'];
        $plan_price = $row['product_price'];
        $plan_discount = $row['discount'];
        $tax = $row['tax'];
    }
    $sql = mysqli_query($conn, "SELECT * FROM employee WHERE admin_id = '$admin_id' AND employee_status = 1 ");
    $no_of_emp +=  mysqli_num_rows($sql);
    $prd_name = $plan_name;   //plan Name
    $price = $plan_price * $month * $no_of_emp; //Service price
    $prd_price = $price;
    $discount = 0;
    if($no_of_emp >= 100){
        $discount = (ceil($prd_price * $plan_discount / 100));
    }
    if($no_of_emp >= 250){
        $plan_discount = $plan_discount + 5;
        $discount = (ceil($prd_price * $plan_discount / 100));
    }
    if($month >= 12){
        $plan_discount = $plan_discount + 5;
        $discount = (ceil($prd_price * $plan_discount / 100));
    }
    $dis = (ceil($prd_price - $discount));
    $total = $dis;
    $gst = $total * $tax / 100;
    $total = $total + $gst;
    $prd_price = (ceil($total));

    $output = ' <ul class="list-group z-depth-0">
                    <li class="list-group-item justify-content-between">Plan Name: ' . $plan_name . '</li>
                    <li class="list-group-item justify-content-between"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon'.' * ' . $no_of_emp . ' Emp) </li>
                    <li class="list-group-item justify-content-between"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</li>
                    <li class="list-group-item justify-content-between">Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</li>   
                </ul>
                <input type="hidden" name="month" id="month" value = '.$month.'> 
                <input type="hidden" name="price" id="price" value = '.$prd_price.'> ';
    echo $output;
}

//get Employee Of the month details
if($_POST['action'] == 'employee_fetch')
{
    $admin_id = $_POST['admin_id'];
    $month = date('m', strtotime('-1 month', time()));
    $years = date("Y");
    $days = cal_days_in_month(CAL_GREGORIAN,$month,$years);
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
    if($working_days == 5 && $day6 == 0 && $day7 == 0){
        $startdate = strtotime($years . '-' . $month . '-01');
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
    }elseif($working_days == 6){
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
    }else{
        $return = $days;
    }

    class employee
    {
        private $date = array();
        function __construct($name)
        {
            $month = date('m', strtotime('-1 month', time()));
            $years = date("Y");
            $days = cal_days_in_month(CAL_GREGORIAN,$month,$years);
            $this->name = $name;
            for($i = 0; $i <= $days; $i++){
                $this->date[] = 0;
            }
        }
        function getDate($i) {
            return $this->date[$i];
        }
        function setDate($date, $i) {
            $this->date[$i] += $date;
        }
    }
    $query = mysqli_query($conn, "select *  from company_time where admin_id = '$admin_id' ");
    while($ron = mysqli_fetch_array($query))
    {
        $b_time = $ron['company_break_time'];
        $company_time = $ron['company_in_time'];
        $timezone = $ron['timezone'];
    }
    $total_time = explode (":", $b_time);
    $b_time = ($total_time[0] * 60) + $total_time[1];
    date_default_timezone_set($timezone);
    $monthName = date("F", mktime(0, 0, 0, $month));

    $fromdt= strtotime("First Day Of  $monthName $years");
    $todt= strtotime("Last Day of $monthName $years");
    $emp_name = explode(",", $_POST['emp_name']);
    $e_id = $emp_name[0];
    $attendance = mysqli_query($conn, "SELECT * FROM attendance where employee_id = '$e_id' and in_time between '$fromdt' and '$todt' ");
    $present = mysqli_num_rows($attendance);
    $break = mysqli_query($conn, "SELECT * FROM break where employee_id = '$e_id' and break_time between '$fromdt' and '$todt' ");
    $emp = array();
    $employee[$e_id] = 0;
    array_push($emp, new employee($employee[$e_id]));
    while($row = mysqli_fetch_array($break)){
        $break_time = $row['break_time'];
        $break_out = $row['out_time'];
        if($break_out != 'OUT')
        {
            $diff = $break_out - $break_time;
        }
        $diffBreak = round($diff / 60);
        $emp[$employee[$e_id]]->setDate($diffBreak,date('j',$break_time));
    }
    $break = 0;
    for($i = 1; $i <= $days ; $i ++){
        if($emp[0]->getDate($i) > $b_time){
            $break++;
        }
    }
    $attendance = round(($present/$return)*100)."%";
    $break = round(($break/$return)*100)."%";
    $leave = $return - $present;
    if($leave < -1){
        $leave = 0;
    }
    $arr = array(
        "break" => $break,
        "attendance" => $attendance,
        "leave" => $leave
    );
    echo json_encode($arr);
}

// fetch salary settings
if($_POST['action'] == 'salary_settings'){
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $fetch = mysqli_query($conn,"SELECT * FROM salary_setting WHERE admin_id = '$admin_id' ");
    $row = mysqli_fetch_array($fetch);
    echo json_encode($row);
}

// Fetch salary
if($_POST['action'] == 'fetch_salary')
{
    $months = $_POST['month'];
    $years= $_POST['year'];
    $admin_id= $_POST['admin_id'];
    $f_date = strtotime(date("1-$months-$years 00:00:00"));
    $t_date = strtotime(date("30-$months-$years 23:59:59"));
    $heading = mysqli_query($conn, "SELECT * FROM  `salary_setting`  where admin_id = '$admin_id' ");
    // echo "SELECT * FROM staff_salary where admin_id = '$admin_id' and salary_status = 1 and staff_salary_date between '$f_date' and '$t_date' ";
    $sql = mysqli_query($conn, "SELECT * FROM staff_salary where admin_id = '$admin_id' and salary_status = 1 and staff_salary_date between '$f_date' and '$t_date' ");
    $output = '<table id="salary" class="table table-striped custom-table table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th style=".DTFC_LeftBodyLiner { overflow-x: hidden; }">Employee Name</th>
                            <th>Basic</th>
                            <th>Working Days</th>
                            <th>Present Days</th>
                            <th>Absent Days</th>';
    $ro = mysqli_fetch_array($heading);
    if($ro["hra"] != 'not used') {
        $output .= '<th>HRA</th>';
    }
    if($ro["conveyance"] != 'not used') {
        $output .='<th > Conveyance</th >';
    }
    if($ro["pf"] != 'not used') {
        $output .='<th > PF</th >';
    }
    $output .='<th>Over Time</th>
                <th>Incentive</th>
                <th>Leave</th>
                <th>Break Violation</th>
                <th>Late Violation</th>
                <th>Net Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="salary-body" >';
    $no = 1;
    while ($row = mysqli_fetch_array($sql))
    {
        $output .= '<tr data-id='.$row["salary_id"].' >
                        <td data-id='.$row["salary_id"].' >'.$row["emp_name"].'</td>
                        <td><center>'.$row["basic"].'</center></td>
                        <td><center>'.$row["working_days"].'</center></td>
                        <td><center>'.$row["present_days"].'</center></td>
                        <td><center>'.$row["absent_days"].'</center></td>';
        if($ro["hra"] != 'not used') {
            $output .= '<td><center>'.(float)$row["hra"].'</center></td>';
        }
        if($ro["conveyance"] != 'not used') {
            $output .='<td><center>'.(float)$row["conveyance"].'</center></td>';
        }
        if($ro["pf"] != 'not used') {
            $output .='<td><center>'.(float)$row["pf"].'</center></td>';
        }
        $output .='<td ><center>'.$row["over_time"].'</center></td>
                                  <td><center>'.$row["incentive"].'</center></td>
                                  <td><center>'.$row["e_leave"].'</center></td>
                                  <td><center>'.$row["break_violation"].'</center></td>
                                  <td><center>'.$row["late_fine"].'</center></td>
                                  <td><center>'.$row["net_salary"].'</center></td>
                                  <td>
                                      <a href="#" id="'.$row["salary_id"].'" class="update" data-toggle="modal" data-target="#edit_salary"><i class="fa fa-pencil" aria-hidden="true" style=" color:black; font-size:15px;"></i></a>&nbsp;
                                      <a href="#" id="'.$row["salary_id"].'" class="delete"><i class="fa fa-trash-o" aria-hidden="true" style="color:black; font-size:15px;"></i></a>&nbsp;&nbsp;
                                      <a href="pdf\invoice-db.php?id='.$row["salary_id"].'" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red; font-size:15px;"></i></a>
                                  </td>
                                </tr>';
    }
    $output .= '<tbody>
            </table>';
    echo $output;
}

// Fetch Resignation Data
if ($_POST['action'] == 'resignation_fetch') {
    include '../dbconfig.php';
    $sql = mysqli_query($conn, "select * from resignation INNER JOIN employee ON employee.e_id = resignation.e_id INNER JOIN departments ON departments.departments_id = employee.department where resignation.delete_status = '0' AND resignation.admin_id = '" . $_POST['admin_id'] . "' ");
    $output = '<table id="resignation" class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Resigning Employee </th>
                                <th>Department </th>
                                <th>Reason </th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="3">Notice Date</marquee> </th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="3">Resignation Date</marquee> </th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="">';
    $no = 1;
    while ($row = mysqli_fetch_array($sql)) {
        if ($row['request_status'] == 00) {
            $status = '<span class="view_data"  style="background-color: blue;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Pending</a>';
        } else if ($row['request_status'] == 11) {
            $status = '<span class="view_data"   style="background-color: green;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Approved</a>';
        } else {
            $status = '<span class="view_data"  style="background-color: red;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Decline</a>';
        }
        $m_id = explode(" ",$row['reason']);
        $output .= '
                            <tr>
                                <td>' . $no++ . '</td>
                                <td>
                                    <h2 class="table-avatar blue-link">
                                        <a href="profile.php?id='.$row['e_id'].'" class="avatar"><img alt="" src="../employee/employee_profile/' . $row['employee_profile'] . ' " height="100%" ></a>
                                        <a href="profile.php?id='.$row['e_id'].'" style="color: black">' . ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']) . '</a>
                                    </h2>
                                </td>
                                <td>' . $row['departments_name'] . '</td>
                                <td><a href="#" style="color: black" title="View Reason" data-toggle="modal" data-target="#reason'.$m_id[0].$row['resignation_id'].'"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                  <div class="modal fade" id="reason'.$m_id[0].$row['resignation_id'].'" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h4 class="modal-title">Reason</h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                          <p class="text-justify">'. $row['reason'] . '</p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div></td>
                                <td>' . date("d/m/Y", $row['notice_date']) . '</td>
                                <td>' . date("d/m/Y", $row['resignation_date']) . '</td>
                                <td>' . $status . '</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(65px, 32px, 0px);">
                                            <a class="dropdown-item edit_data" id=' . $row['resignation_id'] . ' href="#" style="color:black" data-toggle="modal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class=" dropdown-item delete" id=' . $row['resignation_id'] . ' href="#" style="color:black" data-toggle="modal" data-target="#delete_resignation"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            <a class="dropdown-item" href="resignation_print.php?id=' . $row['resignation_id'] . ' " target="_blank" style="color:black"><i class="fa fa-print m-r-5"></i> Print</a>
                                        </div>
                                    </div>    
                                </td>
                            </tr>
                    ';
    }
    $output .= '</tbody>
                    </table>';
    echo $output;
}

// view logo

if($_POST['action'] == 'view_logo')
{

$sql = mysqli_query($conn," select * from company_logo where admin_id = '".$_POST['admin_id']."' ");
$output = '';
while ($row = mysqli_fetch_array($sql))
{
    $output .= '<img src="company_document/'.$row['logo_name'].'" width="100" height="80">
            <input type="hidden" id="logo_id" value="'.$row['logo_id'].'">
                ';
}
echo $output;
}

// fetch Employee_details

if($_POST['action'] == 'fetch_employee')
{

    $data = array();
    $sql2 = mysqli_query($conn, "select * from employee_profile inner join employee on employee.e_id = employee_profile.e_id where employee_profile.e_id = '".$_POST['id']."' AND employee_profile.admin_id = '".$_POST['admin_id']."' ");
    $row1 = mysqli_fetch_array($sql2);


    $sql3 = mysqli_query($conn, "select * from employee_bank_detail where e_id = '".$_POST['id']."' ");
    $row2 = mysqli_fetch_array($sql3);


    $sql4 = mysqli_query($conn, "select * from employee_emergemcy_contact where e_id = '".$_POST['id']."' ");
    $row3 = mysqli_fetch_array($sql4);

    $data[0]= $row1;
    $data[1]= $row2;
    $data[2]= $row3;


    echo json_encode($data);
}

// view logo

if($_POST['action'] == 'employee_of_month')
{
    $output ='<table id="users" class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th><b>No</b></th>
                        <th><b>Name</b></th>
                        <th><b>Created Date</b></th>
                        <th class="text-right"><b>Action</b></th>
                    </tr>
                </thead>
                    <tbody>';

                        $sql = mysqli_query($conn, "SELECT * FROM break_off_user where admin_id = " . $_POST['admin_id'] . " ");
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) {

                             $output .='<tr>
                                            <td>'.$no++.'</td>
                                            <td >'.$row['emp_name'].'</td>
                                            <td >'.date("d/m/Y",$row['add_date']).'</td>
                                            <td class="text-right">
                                                <a class="delete_user" href="#" id="'.$row['bo_id'].'" style="color:black" title="Delete" data-toggle="modal" data-target="#delete_user" ><i class="fa fa-trash m-r-5"></i></a>
                                            </td>
                                        </tr>';
                        }
                    $output .= '</tbody>
                </table>';
echo $output;
}

// Fetch Employee Profile

if($_POST['action'] == 'fetch_employee_profile')
{
        include '../dbconfig.php';
        $bank_output = '';
        $sql3 = mysqli_query($conn, "select * from employee_bank_detail where e_id = '" . $_POST['id'] . "' AND admin_id = '" . $_POST['admin_id'] . "' ");
        while ($r3 = mysqli_fetch_array($sql3)) {

            $bank_output .= '<li>
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
                        <div class="title">PAN No</div>
                        <div class="text">' . $r3['eb_pan_no'] . '</div>
                    </li>';
        }
        // Employye Profile
    $employee_profile = '';
    $sql2 = mysqli_query($conn, "select * from employee_profile inner join employee on employee_profile.e_id = employee.e_id where employee_profile.e_id = '" . $_POST['id'] . "' AND employee_profile.admin_id = '" . $_POST['admin_id'] . "' ");
    while ($r = mysqli_fetch_array($sql2)) {
        $employee_profile .= '<li>
                        <div class="title">Birthday:</div>
                        <div class="text">' . date("d-m-Y",strtotime($r['date_of_birth'])) . '</div>
                    </li>
                    <li>
                        <div class="title">Address:</div>
                        <div class="text">' . $r['emp_address'] . '</div>
                    </li>
                    <li>
                        <div class="title">Gender:</div>
                        <div class="text">' . $r['e_gender'] . '</div>
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
    }
    // Employee Emergency contact Details
    $emergency_contact = '';
    $sql3 = mysqli_query($conn, "select * from employee_emergemcy_contact where e_id = '".$_POST['id']."' AND admin_id = '" . $_POST['admin_id'] . "' ");
    while ($r3 = mysqli_fetch_array($sql3)) {

        $emergency_contact = '<li>
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
    }
    $data = array();
    $data[0] = $bank_output;
    $data[1] = $employee_profile;
    $data[2] = $emergency_contact;
    echo json_encode($data);
}

if($_POST['action'] == 'view_company_time'){
    $sql = mysqli_query($conn, "select * from company_time where admin_id = '" . $_POST['admin_id'] . "' ");
    $data = array();
    while($row = mysqli_fetch_array($sql)){
        array_push($data,$row);
    }
    echo json_encode($data);
}

if($_POST['action'] == 'shift_fetch'){
    $sql = mysqli_query($conn, "select * from company_time where time_id = '" . $_POST['shift_id'] . "' ");
    $row = mysqli_fetch_array($sql);
    echo json_encode($row);
}

if($_POST['action'] == 'employeecard_fetch'){
    $id = $_POST['id'];
    $sql = mysqli_query($conn, "SELECT * FROM employee_card WHERE admin_id = ' " .$_POST['admin_id']. "' AND device_type = '$id' AND assign_status = '0' ");
    $data = array();
    while($row = mysqli_fetch_assoc($sql)){
        array_push($data,$row);
    }
    echo json_encode($data);
}

if($_POST['action'] == 'shift_change'){
    if(!isset($_SESSION)) { session_start(); }
    $shiftId = $_POST['shift'];
    if(isset($_SESSION['userType'])){
        if($_SESSION['userType'] == 'admin'){
            $id = $_SESSION['admin_id'];
            $sql = "UPDATE `company_admin` SET `shift_id` = '$shiftId' WHERE `company_admin`.`admin_id` = '$id' ";
            $_SESSION['shift_id'] = $shiftId;
        }else{
            $id = $_SESSION['user_id'];
            $sql = "UPDATE `add_users` SET `shift_id` = '$shiftId' WHERE `add_users`.`user_id` = '$id' ";
            $_SESSION['shift_id'] = $shiftId;
        }
    }else{
        $_SESSION['shift_id'] = $shiftId;
    }
}

if($_POST['action'] == 'show_joining'){
    
            $sql = mysqli_query($conn, "select * from joining_employee INNER JOIN employee ON employee.e_id = joining_employee.e_id INNER JOIN departments ON departments.departments_id = joining_employee.department_name where joining_employee.admin_id = '" . $_SESSION['admin_id'] . "' AND joining_employee.joining_delete = '0' ORDER BY added_time DESC ");
            $output = '<table id="joining_data" class="table table-striped custom-table table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Joining Employee</th>
                                <th>Employee Id</th>
                                <th>Department</th>
                                <th>Joining Date </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
            $no = 1;
            while ($row = mysqli_fetch_array($sql)) {
                $output .= '<tr>
                                <td>' . $no++ . '</td>
                                <td>' . ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']) . '</td>
                                <td>' . $row['emp_id'] . '</td>
                                <td>' . $row['departments_name'] . '</td>
                                <td>' . date('d/m/Y',$row['joining_date']) . '</td>
                                <td class="text-right">        
                                            <a class="edit_data" style="color:black" href="#" id="' . $row['joining_id'] . '" data-toggle="modal"><i class="fa fa-pencil m-r-5"></i></a>
                                            <a class="delete_data" style="color:black" href="#" id="' . $row['joining_id'] . '" data-toggle="modal"><i class="fa fa-trash-o m-r-5"></i></a>
                                            <a class="print_data" style="color:#e7000d" href="joining_print.php?id=' . $row['joining_id'] . '" target="_blank"><i class="fa fa-print m-r-5"></i></a>
                                </td>
                            </tr>';
            }
        $output .='</tbody>
                    </table>';
        echo $output;
}

if($_POST['action'] == 'fetch_joining'){
    $sql = mysqli_query($conn, "SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id WHERE e_id = '" . $_POST['e_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['join_date'] = date('Y-m-d',$row['join_date']);
        echo json_encode($row);
}



 if($_POST['action'] == 'fetch_editjoining'){
    $sql = mysqli_query($conn, "select * from joining_employee INNER JOIN employee ON employee.e_id = joining_employee.e_id INNER JOIN departments ON departments.departments_id = joining_employee.department_name where joining_employee.joining_id = '" . $_POST['joining_id'] . "' ");
    $row = mysqli_fetch_array($sql);
    $row['joining_date'] = date("Y-m-d",$row['joining_date']);
    echo json_encode($row);
        
 }


 if($_POST['action'] == 'reaming_employee'){
  $sql = $conn->query( "select * from `company_admin` where admin_id = '" . $_SESSION['admin_id'] . "' ");
    $row = $sql->fetch_assoc();
    $api = new Util();
    $admin_id = $_SESSION['admin_id'];
    $new = $api->getnewEmployeecount($admin_id,$conn);
    $left = $api->getleftEmployeecount($admin_id,$conn);
    $array = array(
        'total' => $row['totalemployee'],
        'remaining' => $row['remainingemployee'],
        'planend' => $row['plan_end'],
        'new' => $new,
        'left' => $left
    );
    echo json_encode($array);
 }

 if($_POST['action'] == 'fetch_invoice'){
     $stripe = new Stripeupdate();
     $postdata = $_POST['data'];
     $stripe->fatchinvoice($postdata);
 }

 if($_POST['action'] == 'manageBilling'){
     $stripe = new Stripeupdate();
     $stripe->manageBilling();
 }
 
 if($_POST['action'] == 'postman'){
    $_SESSION['admin_id'] = 1;
    $_SESSION['device_username'] = 'admin';
    $_SESSION['device_password'] = 'sphikvision@123';
    $event = new Util();
    $res = $event->refreshPagedata( $conn,'B56D8DAC-49EC-4DD1-9B1A-2ED6DB0FB091');
    echo json_encode($res);
 }
 // Fetch designation Data
if($_POST['action'] == 'getOffice')
{
    $sql = $conn->query("Select * from office where id = ".$_POST['id']."");
    $row = $sql->fetch_assoc();
    echo json_encode($row);
}

if($_POST['action'] == 'getAllowances')
{
    if($_POST['id'] == ""){
        $query = "SELECT * FROM allowances   where admin_id = " . $_POST['admin_id'] ;
    }else{
        $query = "SELECT * FROM allowances   where id = " . $_POST['id'] ;
    }
    $sql = $conn->query($query);
    $no = 1;
    $output = '<table id="a_data" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name </th>
                            <th>Description </th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
    $data = array();
    while($row = mysqli_fetch_assoc($sql))
    {
        $data = $row;
        $output .=' <tr>
                        <td>'.$no++.'</td>
                        <td>'.$row['allowances'].'</td>
                        <td>'.$row['description'].'</td>
                        <td class="text-right">
                            <a class="edit" href="#" onClick="getAllowances('.$row['id'].')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a
                        <td>
                    </tr>';
    }
    $output .='</tbody>
            </table>';
    if($_POST['id'] != ""){
        echo json_encode($data);
    }else{
        echo $output;
    }
}

if($_POST['action'] == 'getDeductions')
{
    
    if($_POST['id'] == ""){
        $query = "SELECT * FROM deductions where admin_id = " . $_POST['admin_id'] ;
    }else{
        $query = "SELECT * FROM deductions where id = " . $_POST['id'] ;
    }
    $sql = $conn->query($query);
    $no = 1;
    $output = '<table id="d_data" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name </th>
                            <th>Description </th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
    $data = array();
    while($row = mysqli_fetch_assoc($sql))
    {
        $data = $row;
        $output .=' <tr>
                        <td>'.$no++.'</td>
                        <td>'.$row['deductions'].'</td>
                        <td>'.$row['description'].'</td>
                        <td class="text-right">
                            <a class="edit" href="#" onClick="getDeductions('.$row['id'].')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a
                        <td>
                    </tr>';
    }
    $output .='</tbody>
            </table>';
    if($_POST['id'] != ""){
        echo json_encode($data);
    }else{
        echo $output;
    }
}

if($_POST['action'] == 'getusers'){
    $value = "%". $_POST['query']."%";
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM employee WHERE admin_id = '$admin_id' and employee_status = 1 and delete_status = 0 and e_firstname LIKE '$value' ";
    $sql = $conn->query($query);
    $no = 1;
    $output = '';
    while($row = $sql->fetch_assoc())
    {
        $name = $row['e_firstname'].' '.$row['e_lastname'];
        $id= $row['e_id'];
        $output .=' <option data-id="'.$id.'" value="'.$no.'.'.$name.'">';
    }
    if($sql->num_rows == 0){
        $output = '<option data-id="" value="no result found">';
    }
    echo $output;
}

if($_POST['action'] == 'getuservalues'){
    $Id = $_POST['id'];
    $query = "SELECT * from employee WHERE e_id = '$Id' ";
    $sql = $conn->query($query);
    $no = 1;
    $output = '<br><table id="a_data" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Add/Deduct Type</th>
                            <th>Amount/Precentage</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
    $row = $sql->fetch_assoc();
    $da = $row['da'];
    $id = $row['e_id'];
    $hra = $row['hra'];
    $conveyance = $row['conveyance'];
    $spallow = $row['m_allow'];
    $tds = $row['tds'];
    $esi = $row['esi'];
    $pf = $row['pf'];
    $salary = $row['e_salary'];
    $proftax = $row['proftax'];
    $l_wel = $row['l_wel'];
    $daType = $row['daType'] == "+" ? "Amount" : "Percentage";
    $hraType = $row['hraType'] == "+" ? "Amount" : "Percentage";
    $conveyanceType = $row['conveyanceType'] == "+" ? "Amount" : "Percentage";
    $spallowType = $row['m_allowType'] == "+" ? "Amount" : "Percentage";
    $tdsType = $row['tdsType'] == "+" ? "Amount" : "Percentage";
    $esiType = $row['esiType'] == "+" ? "Amount" : "Percentage";
    $pfType = $row['pfType'] == "+" ? "Amount" : "Percentage";
    $proftaxType = $row['proftaxType'] == "+" ? "Amount" : "Percentage";
    $l_welType = $row['l_welType'] == "+" ? "Amount" : "Percentage";
    $daf = "'da'";
    $basicf = "'{$salary}'";
    $hraf = "'hra'";
    $conf = "'conveyance'";
    $spallwf = "'m_allow'";
    $tdsf = "'tds'";
    $esif = "'esi'";
    $pff = "'pf'";
    $proftaxf = "'proftax'";
    $lwelf = "'l_wel'";
    $output .=' <tr class="table-primary">
                    <td>'.$no++.'</td>
                    <td>Basic</td>
                    <td>CTC</td>
                    <td>Amount</td>
                    <td>'.$salary.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="editBasicValue('.$id.', '.$basicf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr>
                <tr class="table-success">
                    <td>'.$no++.'</td>
                    <td>Allowence</td>
                    <td>Driver All.</td>
                    <td>'.$daType.'</td>
                    <td>'.$da.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$daf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr>
                <tr class="table-success">
                    <td>'.$no++.'</td>
                    <td>Allowence</td>
                    <td>HRA</td>
                    <td>'.$hraType.'</td>
                    <td>'.$hra.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$hraf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-success">
                    <td>'.$no++.'</td>
                    <td>Allowence</td>
                    <td>Conv. All</td>
                    <td>'.$conveyanceType.'</td>
                    <td>'.$conveyance.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$conf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-success">
                    <td>'.$no++.'</td>
                    <td>Allowence</td>
                    <td>Sp. Allo</td>
                    <td>'.$spallowType.'</td>
                    <td>'.$spallow.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$spallwf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-warning">
                    <td>'.$no++.'</td>
                    <td>Deduction</td>
                    <td>PF</td>
                    <td>'.$pfType.'</td>
                    <td>'.$pf.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$pff.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-warning">
                    <td>'.$no++.'</td>
                    <td>Deduction</td>
                    <td>ESI</td>
                    <td>'.$esiType.'</td>
                    <td>'.$esi.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$esif.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-warning">
                    <td>'.$no++.'</td>
                    <td>Deduction</td>
                    <td>Proff. Tax</td>
                    <td>'.$proftaxType.'</td>
                    <td>'.$proftax.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$proftaxf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-warning">
                    <td>'.$no++.'</td>
                    <td>Deduction</td>
                    <td>L.W.F</td>
                    <td>'.$l_welType.'</td>
                    <td>'.$l_wel.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$lwelf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>
                </tr><tr class="table-warning">
                    <td>'.$no++.'</td>
                    <td>Deduction</td>
                    <td>T.D.S</td>
                    <td>'.$tdsType.'</td>
                    <td>'.$tds.'</td>
                    <td class="text-right">
                        <a class="edit" href="#" onClick="getEditValue('.$id.', '.$tdsf.')" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                    <td>';
    
    $output .='</tbody>
            </table>';
    echo $output;
}
if($_POST['action'] == 'getEmpADValue'){
    $Id = $_POST['id'];
    $f1 = $_POST['col'];
    $f2 = $_POST['col']."Type";
    $query = "SELECT {$f1},{$f2} FROM employee WHERE e_id = '$Id' ";
    $sql = $conn->query($query);
    if(!$sql){
        echo json_encode(array('status' =>  'error', 'message' => $conn->error));
    }
    $row = $sql->fetch_assoc();
    echo json_encode($row);
}
