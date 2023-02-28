<?php
ob_start();
class control {
// Register Sub Admin and First Step Comapny info
   function register_user() {
       include 'dbconfig.php';
       //first step
       $company_name = mysqli_real_escape_string($conn, $_POST['c_name']);
       $name = mysqli_real_escape_string($conn, $_POST['name']);
       $contact = mysqli_real_escape_string($conn, $_POST['contact']);
       $no_of_employee = mysqli_real_escape_string($conn, $_POST['no_emp']);
       $email = mysqli_real_escape_string($conn, $_POST['email']);
#       $token = bin2hex(random_bytes(50));
       $token = 'yes';
       $plan = mysqli_real_escape_string($conn, $_POST['plan']);
       $price = mysqli_real_escape_string($conn, $_POST['price']);
       $month = mysqli_real_escape_string($conn, $_POST['month']);
       $password = hash('sha1', mysqli_real_escape_string($conn, $_POST['password']));
       $id = mysqli_real_escape_string($conn, $_POST['id']);
       $hardware_type = $_POST['hardware_type'];
       $device_status = $_POST['confirm'];
        $time = time();
       if ($id == '') {
           $sql = "INSERT INTO `registration`(`company_name`, `name`, `contact_number`, `company_email`, `password`, `no_of_employee`, `activation_code`, `plan`, `month`, `entry_date`, `hardware_type`, `want_device`) VALUES ('$company_name','$name','$contact','$email','$password','$no_of_employee','$token','$plan','$month','$time','$hardware_type','$device_status')";
#           echo $sql;
           $conn->query($sql);
           $lastid = mysqli_insert_id($conn);
           echo $lastid;
       } else {
           $sql = "update registration set company_name = '$company_name', name = '$name', contact_number = '$contact', password = '$password', company_email = '$email',no_of_employee = '$no_of_employee', plan = '$plan', month = '$month' , hardware_type = '$hardware_type', want_device = '$device_status' where registration_id = '$id' ";
            $conn->query($sql);
           echo $id;
       }
   }
   
    function addemployee() {
        include 'dbconfig.php';
        $f_name = mysqli_real_escape_string($conn, $_POST['f_name']);
        $l_name = mysqli_real_escape_string($conn, $_POST['l_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $password = hash('sha1', $_POST['emp_id']);
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $join_date = mysqli_real_escape_string($conn, strtotime($_POST['join_date']));
        $ph_no = mysqli_real_escape_string($conn, $_POST['ph_no']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $salary = mysqli_real_escape_string($conn, $_POST['e_salary']);
        $department = explode(",", $_POST['department']);
        $designation = explode(",", $_POST['designation']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

        $sql1 = "Insert into employee(e_firstname,e_lastname,e_email,e_gender,e_password,emp_cardid,join_date,e_phoneno,department,admin_id,designation)values('$f_name','$l_name','$email','$gender','$password','$emp_id','$join_date','$ph_no','$department[0]','$admin_id','$designation[0]')";
        if ($conn->query($sql1)) {
            $department[1] = $department[1] + 1;
            $designation[1] = $designation[1] + 1;
            $depart = mysqli_query($conn,"update department set used_count = '$department[1]' where departments_id = '$department[0]' ");
            $desig = mysqli_query($conn,"update designation set used_count = '$designation[1]' where departments_id = '$designation[0]' ");
            $sql = mysqli_query($conn, "update employee_card set assign_status = 1 where employee_cardno = '$emp_id' ");
            $subject = "Admin OTP Verification";
            $to = $email;
            $name = mysqli_query($conn, "SELECT * FROM `company_admin` WHERE admin_id = $admin_id ");
            while ($row =  mysqli_fetch_array($name))
            {
                $c_name = $row['company_name'];
            }
            $name = $f_name." ".$l_name;
            include 'email.php';
            $mail->addAddress($email);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Welcome To Windson Payroll';
            $string_to_name = '@@name@@';
            $string_email = '@@email@@';
            $string_password = '@@password@@';
            $string_cname = '@@cname@@';
            // for replace name
            $message = file_get_contents("email-template/new_joining.php");
            $content_chunks=explode($string_to_name, $message);
            $content=implode($name, $content_chunks);
            file_put_contents('email-template/new_joining.php', $content);
            // for replace Admin
            $message = file_get_contents("email-template/new_joining.php");
            $content_chunks=explode($string_password, $message);
            $content=implode($emp_id, $content_chunks);
            file_put_contents('email-template/new_joining.php', $content);
            // for replace Token
            $message = file_get_contents("email-template/new_joining.php");
            $content_chunks=explode($string_email, $message);
            $content=implode($email, $content_chunks);
            file_put_contents('email-template/new_joining.php', $content);
            // for replace Token
            $message = file_get_contents("email-template/new_joining.php");
            $content_chunks=explode($string_cname, $message);
            $content=implode($c_name, $content_chunks);
            file_put_contents('email-template/new_joining.php', $content);

            $mail->Body = file_get_contents("email-template/new_joining.php");
            // for replace name
            $message = file_get_contents("email-template/employee_joining.php");
            file_put_contents('email-template/new_joining.php', $message);
            if($mail->send()){
                echo 'Employee added Successfully.';
            }else{
                echo "Email Not Sent".$mail->ErrorInfo();
            }

        } else {
            echo 'Failed';
      }

    }

    function editemployee() {
        include 'dbconfig.php';

    }

// Add holiay
    function addholiday() {
        include 'dbconfig.php';
        $query = mysqli_query($conn, "select *  from company_time where admin_id = ".$_POST['admin_id']." ");
            while($ron = mysqli_fetch_array($query))
            {
                $break_time = $ron['company_break_time']; 
                $company_time = $ron['company_in_time']; 
                 $timezone = $ron['timezone']; 
            }
            date_default_timezone_set("$timezone");
        $holiday_name = mysqli_real_escape_string($conn, $_POST['holiday_name']);
        $date = mysqli_real_escape_string($conn, strtotime($_POST['holiday_date']));
        $holiday_description = mysqli_real_escape_string($conn, $_POST['holiday_description']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $sql1 = "Insert into holidays(holiday_name,holiday_date,holiday_description,admin_id)
                    values('$holiday_name','$date','$holiday_description','$admin_id')";
        if ($conn->query($sql1)) {
            echo "true";
        } else {
            echo "false";
        }
}
    
    // add Leave Type
    function leave_insert() {
        include 'dbconfig.php';
        $leave_type = mysqli_real_escape_string($conn, $_POST["leave_type"]);
        $admin_id = mysqli_real_escape_string($conn, $_POST["admin_id"]);
        $query = "INSERT INTO add_leave (leave_type, admin_id) VALUES('$leave_type', '$admin_id')";
        if (mysqli_query($conn, $query))
        {
            echo "Leave Type Added";
        }
        else{
            echo "Leave Type Not Added";
        }
    }
        
    // Edit Leave Type
    function leave_edit() {
        include 'dbconfig.php';
        $leave_type = mysqli_real_escape_string($conn, $_POST["leave_type"]);
        $leave_id = mysqli_real_escape_string($conn, $_POST["leave_id"]);
        $query = "UPDATE add_leave SET leave_type = '$leave_type' WHERE leave_id = '$leave_id'";
        if (mysqli_query($conn, $query))
        {
            echo "Leave Type Edited";
        }
        else{
            echo "Leave Type Not Edited";
        }
    }

    // leaves Type are delete here
    function leave_delete() {
        include 'dbconfig.php';
        if (isset($_POST['leave_id'])) {
            $query = "delete from add_leave where leave_id = '" . $_POST['leave_id'] . "' ";
            $output = "";
            if (mysqli_query($conn, $query)) {
                return true;
            }
        }
    }

    function request_leave() {
        include 'dbconfig.php';
        $query = mysqli_query($conn, "select *  from company_time where time_id = " . $_SESSION['shift_no'] . " ");
            while ($r = mysqli_fetch_array($query)) {
                $timezone = $r['timezone'];
            }
        date_default_timezone_set($timezone);
        $leave_id = mysqli_real_escape_string($conn, $_POST["leave_id"]);
        $from_date = mysqli_real_escape_string($conn, strtotime($_POST["from_date"]));
        $to_date = mysqli_real_escape_string($conn, strtotime($_POST["to_date"]));
        $total_days = mysqli_real_escape_string($conn,$_POST["total_days"]);
        $leave_reason = mysqli_real_escape_string($conn, $_POST["leave_reason"]);
        $admin_id = $_POST['admin_id'];
        $e_id = $_POST['e_id'];
        $sql = mysqli_query($conn, "insert into leaves(e_id,admin_id,leave_id,from_date,to_date,number_day,leave_reason)
                             values('$e_id','$admin_id','$leave_id','$from_date','$to_date','$total_days','$leave_reason') ");
        if ($sql) {
            $sql = "SELECT * FROM employee INNER JOIN company_admin ON employee.admin_id = company_admin.admin_id WHERE e_id = '$e_id' ";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $f_name = $row['e_firstname'];
            $l_name = $row['e_lastname'];
            $name = $f_name." ".$l_name;
            $email = $row['admin_email'];
            $emp_id = $row['emp_cardid'];

            include 'mail.php';
            $obj = new Mail();                              // Set email format to HTML
            $subject = 'Leave Request from '.$f_name;
            $string_to_name = '@name@';
            $string_id = '@id@';
            $string_fdate = '@fdate@';
            $string_tdate = '@tdate@';
            $string_days = '@days@';
            $string_reason = '@reason@';
            $from_date = date("d-m-Y",$from_date);
            $to_date = date("d-m-Y",$to_date);
            $template = file_get_contents("email-template/leave_request.php");
            // for replace name
            $template = str_replace($string_to_name, $name, $template);
            // for replace emp_id
            $template = str_replace($string_id, $emp_id, $template);
            // for replace from date
            $template = str_replace($string_fdate, $from_date, $template);
            // for replace to date
            $template = str_replace($string_tdate, $to_date, $template);
            // for replace to total days
            $template = str_replace($string_days, $total_days, $template);
            // for replace to reason
            $template = str_replace($string_reason, $leave_reason, $template);
            if($admin_id == 1){
                $cc = [
                    [
                        'Email' => "dannyosttinc@gmail.com",
                        'Name' => "Danny Parekh"
                    ]
                  ];	
            }else{
    			$cc = [
                        
                      ];	
            }
            $obj->sentApiMail($email, $name ,$template, $subject, $cc);
        }
    }

    // Add Department
    function adddepartment() {
        include 'dbconfig.php';
        $department_name = $_POST['department_name'];
        $admin_id = $_POST['admin_id'];
        $sql = "Insert into departments(departments_name,admin_id)values('$department_name','$admin_id')";
        if ($conn->query($sql)) {
            echo "Department added Successfully.";
        } else {
            echo "Department Not Added";
        }
    }

     // designation 
    function designation() {
        include 'dbconfig.php';
        $designation_name = mysqli_real_escape_string($conn, $_POST['designation_name']);
        $department = explode(",",$_POST['department']);
        $admin_id = $_POST['admin_id'];
        $department_id = $department[0];
        $sql = "insert into designation(designation_name,department_id ,admin_id)values('$designation_name','$department_id','$admin_id') ";
        $count = (int)$department[1];
        $count = $count + 1;
        $query = mysqli_query($conn,"update department set used_count = '$count' where departments_id = '$department_id' ");
        if ($conn->query($sql)) {
            echo "Designation added Successfully.";
        } else {
            echo "Designation Not Added";
        }
    }
    
    function edit_designation() {
        include 'dbconfig.php';
        $designation_name = mysqli_real_escape_string($conn, $_POST['designation_name']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $admin_id = $_POST['admin_id'];
        $d_id = $_POST['d_id'];
        
        $sql = "update designation set designation_name = '$designation_name',department_id = '$department' where designation_id = '$d_id' ";
        if ($conn->query($sql)) {
            echo "Designation Update Successfully.";
        } else {
            echo "Designation Not Added";
        }
    }
    
    // Add policies
    function addpolicies() {
        include 'dbconfig.php';
        $policy = mysqli_real_escape_string($conn, $_POST['policy']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $policy_statuse = 1;
        $sql1 = $conn->query("Insert into company_policy(policy_name,policy_description,policy_department,policies_statuse,admin_id)values('$policy','$description','$department','$policy_statuse','$admin_id')");
        if ($sql1) {
            echo "Policies added Successfully.";
        } else {
            echo "Policie not added, ".$conn->error;
        }
    }
    // add resignation
    function add_resignation() {
        include 'dbconfig.php';
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);
        $notice_date = strtotime($_POST['notice_date']);
        $resignation_date = strtotime($_POST['resignation_date']);
        $e_id = $_POST['e_id'];
        $admin_id = $_POST['admin_id'];
        $added_time = time();
        $sql = "insert into resignation(e_id,admin_id,reason,notice_date,resignation_date,added_time)values ('$e_id','$admin_id','$reason','$notice_date','$resignation_date','$added_time')";
        if($conn->query($sql)){
            echo 'Success';
        }
    }

    // admin add 
    function admin_add_resignation() {
        include 'dbconfig.php';
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);
        $notice_date = mysqli_real_escape_string($conn, strtotime($_POST['notice_date']));
        $resignation_date = mysqli_real_escape_string($conn, strtotime($_POST['resignation_date']));
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $added_time = time();

        $sql = mysqli_query($conn, "insert into resignation(e_id,admin_id,reason,notice_date,resignation_date,added_time)values ('$e_id','$admin_id','$reason','$notice_date','$resignation_date','$added_time') ");
    }

    function add_termination() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);
        $notice_date = mysqli_real_escape_string($conn, strtotime($_POST['notice_date']));
//        $date = strtotime($notice_date);
        $termination_date = mysqli_real_escape_string($conn, strtotime($_POST['termination_date']));
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
//        $t_date = strtotime($termination_date);
        $termination_type = mysqli_real_escape_string($conn, $_POST['termination_type']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $data_added_time = time();
        $sql = mysqli_query($conn, "insert into termination(manager_name,manager_designation,e_id,admin_id,termination_type,termination_date,reason,notice_date,data_added_time)values('$manager_name','$manager_designation','$e_id','$admin_id','$termination_type','$termination_date','$reason','$notice_date','$data_added_time') ");
        if($sql){
            echo 'true';
        }else{
            echo 'false';
        }
    }

    function show_termination() {
        session_start();
        include 'dbconfig.php';
        if ($_SESSION['admin'] == 'yes') {

            $sql = mysqli_query($conn,"select * from termination INNER JOIN company_admin ON company_admin.admin_id = termination.admin_id INNER JOIN employee ON employee.e_id = termination.e_id INNER JOIN departments ON departments.departments_id = employee.department INNER JOIN designation ON designation.designation_id = employee.designation  where termination.admin_id = '" . $_SESSION['admin_id'] . "' AND termination.termination_delete_status = '0' ORDER BY data_added_time DESC ");
            $output = '<table id="termination" class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminated Employee </th>
                                <th>Department</th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="3">Termination Type </marquee></th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="3">Termination Date </marquee></th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="3">Notice Date</marquee> </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="">';
            $no = 1;
            while ($row = mysqli_fetch_array($sql)) {
                $output .= '<tr>
                                <td>' . $no++ . '</td>
                                <td><b>' . ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']) . '</b></td>
                                <td>' . $row['departments_name'] . '</td>
                                <td>' . $row['termination_type'] . '</td>
                                <td>' . date('d M Y',$row['termination_date']) . '</td>
                                <td>' . date('d M Y',$row['notice_date']) . '</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(65px, 32px, 0px);">
                                            <a class="dropdown-item edit_data" style="color:black" href="#" id="' . $row['termination_id'] . '" data-toggle="modal" data-target="#edit_termination"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item delete_data" style="color:black" href="#" id="' . $row['termination_id'] . '" data-toggle="modal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            <a class="dropdown-item" href="termination_print.php?id='.$row['termination_id'].'" target="_blank"><i class="fa fa-print m-r-5"></i> Print</a>
                                </td>
                            </tr>';
            }
            $output .='</tbody>
                    </table>';
        }
        echo $output;
    }

    function delete_termination() {
        include 'dbconfig.php';
//            $sql = "DELETE FROM `termination` WHERE `termination`.`termination_id` = '" . $_POST['termination_id'] . "' ";
        $sql = mysqli_query($conn, " update termination set `termination_delete_status` = '1' where termination_id = '" . $_POST['id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
    }

    function fetch_termination() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, " select * from termination where termination_id = '" . $_POST['termination_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['termination_date'] = date("Y-m-d",$row['termination_date']);
        $row['notice_date'] = date("Y-m-d",$row['notice_date']);
        echo json_encode($row);
    }

    function edit_termination() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);
        $notice_date = mysqli_real_escape_string($conn, strtotime($_POST['notice_date']));
//        $date = strtotime($notice_date);
        $termination_date = mysqli_real_escape_string($conn, strtotime($_POST['termination_date']));
//        $t_date = strtotime($termination_date);
        $termination_type = mysqli_real_escape_string($conn, $_POST['termination_type']);
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
//        $data_added_time = time();
        $sql = mysqli_query($conn, "update termination set manager_name = '$manager_name',manager_designation = '$manager_designation',e_id = '$e_id', termination_type = '$termination_type', termination_date = '$termination_date', reason = '$reason', notice_date = '$notice_date' where termination_id = '" . $_POST['termination_id'] . "' ");
        if($sql){
            echo 'true';
        }else{
            echo 'false';
        }
    }

    function add_experience() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $period_from = mysqli_real_escape_string($conn, strtotime($_POST['period_from']));
        $period_to = mysqli_real_escape_string($conn, strtotime($_POST['period_to']));
        $application_date = mysqli_real_escape_string($conn, strtotime($_POST['application_date']));
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
        $month = mysqli_real_escape_string($conn, $_POST['month']);
        $ex_added_time = time();
        $experiance = $month / 12;
        $sql = mysqli_query($conn,"insert into experience(`manager_name`,`manager_designation`,`e_id`, `admin_id`, `application_date`,`period_from`,`period_to`,`ex_added_time`,`total_experience`)values('$manager_name','$manager_designation','$e_id', '$admin_id', '$application_date','$period_from','$period_to','$ex_added_time','$experiance') ");
        if($sql){
            echo 'true';
        }else{
            echo 'false';
        }
    }

    function show_experience() {
        session_start();
        include 'dbconfig.php';
        if ($_SESSION['admin'] == 'yes') {

            $sql = mysqli_query($conn, "select * from experience INNER JOIN employee ON employee.e_id = experience.e_id INNER JOIN departments ON departments.departments_id = employee.department INNER JOIN designation ON designation.designation_id = employee.designation where experience.admin_id = '" . $_SESSION['admin_id'] . "' AND experience.ex_delete_status = '0' ORDER BY ex_added_time DESC ");
            $output = '<table id="experience" class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Experience Employee </th>
                                <th>Department</th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="2">Period From </marquee></th>
                                <th>Period To </th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="2">Application Date </marquee></th>
                                <th><marquee behavior="scroll" direction="left" scrollamount="2">Total Years </marquee></th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody >';
            $no = 1;
            while ($row = mysqli_fetch_array($sql)) {
                $output .= '<tr>
                                <td>' . $no++ . '</td>
                                <td>' . ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']) . '</td>
                                <td>' . $row['departments_name'] . '</td>
                                <td>' . date('d/m/Y', $row['period_from']) . '</td>
                                <td>' . date('d/m/Y', $row['period_to']) . '</td>
                                <td>' . date('d/m/Y', $row['application_date']) . '</td>
                                <td>' . round($row['total_experience'],1) . '</td>
                                <td class="text-right">        
                                    <div class="dropdown dropdown-action">
                                       <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                       <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(65px, 32px, 0px);">
                                           <a class="dropdown-item edit_data" style="color:black" href="#" id="' . $row['experience_id'] . '" data-toggle="modal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                           <a class="dropdown-item delete_data" style="color:black" href="#" id="' . $row['experience_id'] . '" data-toggle="modal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                           <a class="dropdown-item print_data" style="color:black" href="experiance_print.php?id=' . $row['experience_id'] . '" target="_blank"><i class="fa fa-print m-r-5"></i> Print</a>
                                       </div>
                                    </div>  
                                </td>
                            </tr>';
            }
            $output .= '</tbody>
                    </table>';
        }
        echo $output;
    }

    function fetch_experience() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, " select * from experience where experience_id = '" . $_POST['experience_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['application_date'] = date('Y-m-d',$row['application_date']);
        $row['period_from'] = date('Y-m-d',$row['period_from']);
        $row['period_to'] = date('Y-m-d',$row['period_to']);
        echo json_encode($row);
    }

    function edit_experience() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $period_from = mysqli_real_escape_string($conn,strtotime($_POST['period_from']));
        $period_to = mysqli_real_escape_string($conn,  strtotime($_POST['period_to']));
        $application_date = mysqli_real_escape_string($conn,  strtotime($_POST['application_date']));
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $experience_id = mysqli_real_escape_string($conn, $_POST['experience_id']);
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
        $month = mysqli_real_escape_string($conn, $_POST['month']);
        $experience = $month / 12;

        $sql = mysqli_query($conn, " update experience set manager_name = '$manager_name',manager_designation = '$manager_designation',e_id = '$e_id', period_from = '$period_from', period_to = '$period_to', total_experience = '$experience' where experience_id = '$experience_id' ");
    }

    function delete_experience() {
        include 'dbconfig.php';
//            $sql = "DELETE FROM `termination` WHERE `termination`.`termination_id` = '" . $_POST['termination_id'] . "' ";
        $sql = mysqli_query($conn, " update experience set `ex_delete_status` = '1' where experience_id = '" . $_POST['id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
    }

    function fetch_joining_date() {
        include 'dbconfig.php';

        $sql = mysqli_query($conn, "SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id where employee.e_id = '" . $_POST['e_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['join_date'] = date("Y-m-d",$row['join_date']);
        echo json_encode($row);
    }

    // employee other profile info
    function profile_info() {
        include 'dbconfig.php';

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
        $query1 = mysqli_query($conn, "insert into employee_profile(`e_id`, `admin_id`, `date_of_birth`, `emp_address`, `pin_code`, `state`, `emp_gender`, `alternate_number`, `emp_nationality`, `emp_religion`, `martial_status`, `employee_detail_update`)values('$e_id','$admin_id','$date_of_birth','$emp_address','$pin_code','$state','$emp_gender','$alternate_number','$emp_nationality','$emp_religion','$martial_status','$employee_detail_update') ");
        $query2 = mysqli_query($conn, "insert into employee_emergemcy_contact(`e_id`, `admin_id`, `person_name`, `relationship`, `phone_number`,`data_update_status`)values('$e_id','$admin_id','$person_name','$relationship','$phone_number','$data_update_status')");
        $query3 = mysqli_query($conn, "insert into employee_bank_detail(`e_id`,`admin_id`,`eb_name`, `eb_account_number`, `eb_ifsc_code`,`eb_branch_name`, `eb_pan_no`, `eb_update_status`,`eb_added_time`)values('$e_id','$admin_id','$eb_name','$eb_account_number','$eb_ifsc_code','$eb_branch_name','$eb_pan_no','$eb_update_status','$eb_added_time') ");
        $query4 = mysqli_query($conn, "update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
    }

    function fetch_joining_data() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, "SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id WHERE e_id = '" . $_POST['e_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['join_date'] = date('Y-m-d',$row['join_date']);
        echo json_encode($row);
    }

    function show_joining() {

        include 'dbconfig.php';
        session_start();
        if ($_SESSION['admin'] == 'yes') {

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
        }
        $output .='</tbody>
                    </table>';
        echo $output;
    }

    function add_joining() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $application_date = mysqli_real_escape_string($conn, strtotime($_POST['application_date']));
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
        $added_time = time();
        $sql = mysqli_query($conn, "insert into joining_employee(`application_date`,`e_id`, `admin_id`, `joining_date`,`emp_id`,`department_name`,`added_time`,`manager_name`,`manager_designation`)values('$added_time','$e_id', '$admin_id', '$application_date','$emp_id','$department','$added_time','$manager_name','$manager_designation') ");
        if($sql){
        echo "done";
        }
    }

    function fetch_joining() {
        session_start();
        if ($_SESSION['admin'] == 'yes') {
            include 'dbconfig.php';
            
            $sql = mysqli_query($conn, "select * from joining_employee INNER JOIN employee ON employee.e_id = joining_employee.e_id INNER JOIN departments ON departments.departments_id = joining_employee.department_name where joining_employee.joining_id = '" . $_POST['joining_id'] . "' ");
            $row = mysqli_fetch_array($sql);
            $row['joining_date'] = date("Y-m-d",$row['joining_date']);
            echo json_encode($row);
        }
    }

    function edit_joining() {
        include 'dbconfig.php';
        $e_id = mysqli_real_escape_string($conn, $_POST['e_id']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $application_date = mysqli_real_escape_string($conn,strtotime($_POST['application_date']));
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $joining_id = mysqli_real_escape_string($conn, $_POST['joining_id']);
        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn, $_POST['manager_designation']);
        $added_time = time();
        $sql = mysqli_query($conn, "update joining_employee set manager_name = '$manager_name',manager_designation = '$manager_designation',e_id = '$e_id', department_name = '$department', joining_date = '$application_date', emp_id = '$emp_id' where joining_id = '$joining_id' ");
        if($sql){
            echo 'Success';
        }else{
            echo 'fail';
        }
    }

    function delete_joining() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, " update joining_employee set `joining_delete` = '1' where joining_id = '" . $_POST['id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
    }

    function add_client() {
        include 'dbconfig.php';
        $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
        $admin_username = mysqli_real_escape_string($conn, $_POST['admin_username']);
        $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
        $admin_password = mysqli_real_escape_string($conn, $_POST['admin_password']);
        $company_id = mysqli_real_escape_string($conn, $_POST['company_id']);
        $admin_contact = mysqli_real_escape_string($conn, $_POST['admin_contact']);
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $company_address = mysqli_real_escape_string($conn, $_POST['company_address']);
        $added_time = time();
        mysqli_query($conn, " insert into company_admin (admin_name,admin_username,admin_email,admin_password,company_id,admin_contact,company_name,company_address,Entry_Date)values('$admin_name','$admin_username','$admin_email','$admin_password','$company_id','$admin_contact','$company_name','$company_address','$added_time') ");
    }

    function fetch_client() {
//        echo 'hello';exit();
        include 'dbconfig.php';
        $sql = mysqli_query($conn, "select * from company_admin where admin_id = '" . $_POST['admin_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['company_upload_storage'] = round($row['company_upload_storage'] / 1000) / 1000;
        echo json_encode($row);
    }

    function fetch_resignation() {
//        echo 'hello';exit();
        include 'dbconfig.php';
        $sql = mysqli_query($conn, "select * from resignation where resignation_id = '" . $_POST['resignation_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        $row['notice_date'] = date("Y-m-d", $row['notice_date']);
        $row['resignation_date'] = date("Y-m-d", $row['resignation_date']);
        echo json_encode($row);
    }

    function edit_client() {
        include 'dbconfig.php';
        $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
        $admin_username = mysqli_real_escape_string($conn, $_POST['admin_username']);
        $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
        $admin_password = mysqli_real_escape_string($conn, $_POST['admin_password']);
        $company_id = mysqli_real_escape_string($conn, $_POST['company_id']);
        $admin_contact = mysqli_real_escape_string($conn, $_POST['admin_contact']);
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $company_address = mysqli_real_escape_string($conn, $_POST['company_address']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $company_upload_storage = mysqli_real_escape_string($conn, $_POST['company_upload_storage']);
        $storage = $c0ompany_upload_storage * 1000 * 1000;

        mysqli_query($conn, " UPDATE `company_admin` SET `company_name`='$company_name',`company_address`='$company_address',`admin_name`='$admin_name',`admin_contact`='$admin_contact',`admin_email`='$admin_email',`admin_username`='$admin_username',`admin_password`='$admin_password',`client_id`='$company_id',`company_upload_storage`='$storage' WHERE admin_id = '$admin_id' ");
    }

    function delete_client() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, " update company_admin set `client_delete_status` = '1' where admin_id = '" . $_POST['admin_id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
    }

    function edit_resignations()
    {
        include 'dbconfig.php';
        $reason = mysqli_real_escape_string($conn,$_POST['reason']);
        $notice_date = mysqli_real_escape_string($conn, strtotime($_POST['notice_date']));
        $resignation_date = mysqli_real_escape_string($conn, strtotime($_POST['resignation_date']));
        $e_id = mysqli_real_escape_string($conn,$_POST['e_id']);
        $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);
        $action = mysqli_real_escape_string($conn,$_POST['r_action']);
        $resignation_id = mysqli_real_escape_string($conn,$_POST['resignation_id']);
        $manager_name = mysqli_real_escape_string($conn,$_POST['manager_name']);
        $manager_designation = mysqli_real_escape_string($conn,$_POST['manager_designation']);
        
        mysqli_query($conn," UPDATE `resignation` SET manager_name = '$manager_name',manager_designation = '$manager_designation',`request_status` = '$action',`e_id`='$e_id',`reason`='$reason',`notice_date`='$notice_date',`resignation_date`='$resignation_date' WHERE resignation_id = '$resignation_id' ");
    }
    
    function delete_resignation() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, " update resignation set `delete_status` = '1' where resignation_id = '" . $_POST['id'] . "' ");
        if ($sql) {
            echo 'Deleted successfully.';
        } else {
            echo "Not Deleted";
        }
    }
    
    function profile1()
   {
       include 'dbconfig.php';
       
       $date_of_birth = mysqli_real_escape_string($conn,$_POST['date_of_birth']);
       
       $emp_address = mysqli_real_escape_string($conn,$_POST['emp_address']);
       $emp_gender = mysqli_real_escape_string($conn,$_POST['emp_gender']);
       $emp_nationality = mysqli_real_escape_string($conn,$_POST['emp_nationality']);
       $emp_religion = mysqli_real_escape_string($conn,$_POST['emp_religion']);
       $martial_status = mysqli_real_escape_string($conn,$_POST['martial_status']);
       $emp_pro_id = mysqli_real_escape_string($conn,$_POST['emp_pro_id']);
       $e_id = mysqli_real_escape_string($conn,$_POST['e_id']);
       $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);
       
       $sql = mysqli_query($conn,"select * from employee_profile where emp_pro_id = '$emp_pro_id' ");
       $row = mysqli_num_rows($sql);
       if($row == 0){
           mysqli_query($conn,"insert into employee_profile (date_of_birth,emp_address,emp_gender,emp_nationality,emp_religion,martial_status,e_id,admin_id)values('$date_of_birth','$emp_address','$emp_gender','$emp_nationality','$emp_religion','$martial_status','$e_id','$admin_id') ");
           mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
       }else{
       mysqli_query($conn," update employee_profile set date_of_birth = '$date_of_birth', emp_address = '$emp_address', emp_gender = '$emp_gender', emp_nationality = '$emp_nationality', emp_religion = '$emp_religion', martial_status = '$martial_status' where emp_pro_id = '$emp_pro_id' ");
       mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
     
       }
   }
    
    function profile2()
   {
       include 'dbconfig.php';
       
       $person_name = mysqli_real_escape_string($conn,$_POST['person_name']);
       $relationship = mysqli_real_escape_string($conn,$_POST['relationship']);
       $phone_number = mysqli_real_escape_string($conn,$_POST['phone_number']);
       $er_id = mysqli_real_escape_string($conn,$_POST['er_id']);
       $e_id = mysqli_real_escape_string($conn,$_POST['e_id']);
       $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);
       
       $sql = mysqli_query($conn,"select * from employee_emergemcy_contact where er_id = '$er_id' ");
       $row = mysqli_num_rows($sql);
       if($row == 0){
           mysqli_query($conn,"insert into employee_emergemcy_contact (person_name,relationship,phone_number,e_id,admin_id)values('$person_name','$relationship','$phone_number','$e_id','$admin_id') ");
           mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
       }else{
       mysqli_query($conn," update employee_emergemcy_contact set person_name = '$person_name', relationship = '$relationship', phone_number = '$phone_number' where er_id = '$er_id' ");
       mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
   }
   }
    
   function profile3()
   {
       include 'dbconfig.php';
       
       $eb_name = mysqli_real_escape_string($conn,$_POST['eb_name']);
       $eb_account_number = mysqli_real_escape_string($conn,$_POST['eb_account_number']);
       $eb_ifsc_code = mysqli_real_escape_string($conn,$_POST['eb_ifsc_code']);
       $eb_pan_no = mysqli_real_escape_string($conn,$_POST['eb_pan_no']);
       $eb_id = mysqli_real_escape_string($conn,$_POST['eb_id']);
       $e_id = mysqli_real_escape_string($conn,$_POST['e_id']);
       $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);
       
       $sql = mysqli_query($conn,"select * from employee_bank_detail where eb_id = '$eb_id' ");
       $row = mysqli_num_rows($sql);
       if($row == 0){
           mysqli_query($conn,"insert into employee_bank_detail (eb_name,eb_account_number,eb_ifsc_code,eb_pan_no,e_id,admin_id)values('$eb_name','$eb_account_number','$eb_ifsc_code','$eb_pan_no','$e_id','$admin_id') ");
           mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
       }else{
           mysqli_query($conn," update employee_bank_detail set eb_name = '$eb_name', eb_account_number = '$eb_account_number', eb_ifsc_code = '$eb_ifsc_code',eb_pan_no = '$eb_pan_no' where eb_id = '$eb_id' ");
           mysqli_query($conn,"update employee set emp_update_status = '1' where e_id = '$e_id' AND admin_id = '$admin_id' ");
       }
   }
    
    function company_profile()
    {
        include 'dbconfig.php';
        
        $sql = mysqli_query($conn," select * from company_admin where admin_id = '".$_POST['admin_id']."' ");
        $row = mysqli_fetch_array($sql);
        echo json_encode($row);
    }
    
    function edit_company_profile()
    {
        include 'dbconfig.php';
        
        $company_name = mysqli_real_escape_string($conn,$_POST['company_name']);
        $admin_name = mysqli_real_escape_string($conn,$_POST['admin_name']);
        $company_address = mysqli_real_escape_string($conn,$_POST['company_address']);
        $country = mysqli_real_escape_string($conn,$_POST['country']);
        $city = mysqli_real_escape_string($conn,$_POST['city']);
        $state = mysqli_real_escape_string($conn,$_POST['state']);
        $pin_code = mysqli_real_escape_string($conn,$_POST['pin_code']);
        $admin_email = mysqli_real_escape_string($conn,$_POST['admin_email']);
        $admin_contact = mysqli_real_escape_string($conn,$_POST['admin_contact']);
        $fax = mysqli_real_escape_string($conn,$_POST['fax']);
        $admin_id = mysqli_real_escape_string($conn,$_POST['admin_id']);
        
        $sql = mysqli_query($conn, " update company_admin set company_name = '$company_name', admin_name = '$admin_name', company_address = '$company_address', country = '$country',city = '$city', state = '$state', pin_code = '$pin_code', admin_email = '$admin_email',admin_contact = '$admin_contact',fax = '$fax' where admin_id = '$admin_id' ");
        if($sql)
        {
            echo 'Profile Update Successfully';
        }else{
            echo 'Profile Update Failed';
        }
    }
    
    function add_company_time() {
        include 'dbconfig.php';
        
        $time1 = mysqli_real_escape_string($conn, $_POST['time1']);
        $time2 = mysqli_real_escape_string($conn, $_POST['time2']);
        $time3 = mysqli_real_escape_string($conn, $_POST['time3']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $timezone = mysqli_real_escape_string($conn, $_POST['timezone']);
        $added_time = time();
        
        $sql = mysqli_query($conn,"select * from company_time where admin_id = '$admin_id' ");
        $count = mysqli_num_rows($sql);
        
        if($count == 1)
        {
            mysqli_query($conn,"UPDATE `company_time` SET `company_in_time`='$time1',`timezone`='$timezone',`company_out_time`='$time2',`company_break_time`='$time3',`entry_time`='$added_time' WHERE admin_id = '$admin_id' ");
        }
        else
        {
            mysqli_query($conn, "INSERT INTO `company_time`(`company_in_time`,`timezone`, `company_out_time`, `company_break_time`, `admin_id`, `entry_time`) VALUES ('$time1','$timezone','$time2','$time3','$admin_id','$added_time')");
        }
            
    }
    
    function view_company_time() {
        include 'dbconfig.php';
        $sql = mysqli_query($conn, "select * from company_time where admin_id = '" . $_POST['admin_id'] . "' ");
        $row = mysqli_fetch_array($sql);
        echo json_encode($row);
    }
    // Edit Holiday Data

   function edit_data() {
       include 'dbconfig.php';
       $name = mysqli_real_escape_string($conn, $_POST['name']);
       $id = $_POST['id'];
       $desc = mysqli_real_escape_string($conn, $_POST['desc']);
       $date = strtotime($_POST['date']);
       $sql = "Update `holidays` set `holiday_name` = '$name',`holiday_date` = '$date', holiday_description = '$desc' where holiday_id = '$id' ";
       if ($conn->query($sql)) {
           echo "Department Update Successfully.";
       } else {
           echo "Department Not Update";
       }
   }

 // department_edit

   function department_edit() {
       include 'dbconfig.php';
       $id = $_POST['d_id'];
       $name = $_POST['d_name'];
        $sql = "update departments set departments_name = '$name'  where departments_id =  $id ";
       if ($conn->query($sql)) {
           echo "Department update Successfully.";
       } else {
           echo "Department Not update";
       }
   }

   // add working days
   function add_working_days() {
       include 'dbconfig.php';

       $sun = $_POST['sun'];
       $mon = $_POST['mon'];
       $tue = $_POST['tue'];
       $wed = $_POST['wed'];
       $thu = $_POST['thu'];
       $fri = $_POST['fri'];
       $sat = $_POST['sat'];
       $admin_id = $_POST['admin_id'];
       $added_time = time();
       
       $sql = mysqli_query($conn,"SELECT * From working_days where admin_id = '$admin_id' ");
       if ($sql->num_rows > 0){
        mysqli_query($conn,"UPDATE working_days SET sun='$sun',mon='$mon',tue=$tue,wed='$wed',thu='$thu',fri='$fri',sat='$sat' where admin_id = '$admin_id' ");
       }else{
       mysqli_query($conn, "INSERT INTO working_days (sun,mon,tue,wed,thu,fri,sat,admin_id,added_time)values('$sun','$mon','$tue','$wed','$thu','$fri','$sat','$admin_id','$added_time')");
       }
        $allow_leave = $_POST['allow_leave'];
      $query = mysqli_query($conn, "select *  from company_benefits where admin_id = " . $_POST['admin_id'] . " ");
      $count = mysqli_num_rows($query);
      if($count == 0){
        $sql1 = mysqli_query($conn,"Insert into company_benefits (admin_id,allow_leave,entry_time)values('$admin_id','$allow_leave','$added_time')");
            if ($sql1) {
                echo "Company Benefits added Successfully.";

            }else{
                echo "Company Benefits Not Added.......";
            }
      }else{
          $sql1 = mysqli_query($conn,"update company_benefits set allow_leave = '$allow_leave',entry_time = '$added_time' where admin_id = '$admin_id'");
            if ($sql1){
                echo "Company Benefits updated Successfully.";

            }else{
                echo "Company Benefits Not Added.......";
            }
      }
   }
    
}

?>