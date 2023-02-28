<?php
ob_start();
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include'emp_header.php';
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="welcome-box">
                    <div class="welcome-img">
                        <img alt="" src="employee_profile/<?php echo $e['employee_profile']; ?> ">
                    </div>
                    <div class="welcome-det">
                        <h3>Welcome, <?php echo $e['e_firstname'] . '&nbsp;' . $e['e_lastname']; ?></h3>
                        <p><?php echo date("l, jS F Y"); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="card flex-fill">
                    <div class="card-body" >
                        <h4 class="card-title">Break Statistics</h4>
                        <center>
                            <div id="break_info" style="padding: 50px">

                            </div>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="bg-gradient-info" style=" padding: 0.1rem 1.25rem; margin-bottom: 0;  background-color: rgba(0,0,0,.03);  border-bottom: 1px solid rgba(0,0,0,.125);">
                        <center><h4> Notice Board</h4></center>						
                    </div>
                    <marquee behavior="scroll" direction="up" style="max-height:300px; min-height:300px;" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">
                        <div class="card-body marquee holder" id="marquee" >
                            <ul class="media-list" id="ticker01" >
                                <li class="media" id="noticescroll">
                                    <div class="mr-3">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>

                                    <div class="media-body">
                                        <a href="#">Welcome to the your account.</a>
                                        <span class="text-muted font-size-sm" style="text-align: right;">(02-12-2019 08:45:52am)</span> <br>
                                        Welcome to your account. Here you can manage to apply for leaves, view leave history, view Attendance history, view salary history, view profile. Start using this today.											
                                    </div>
                                </li>
                                <?php
                                $sql = mysqli_query($conn, "Select * from notice where show_status = 1 and admin_id = " . $_SESSION['admin_id'] . " ");
                                while ($row = mysqli_fetch_array($sql)) {
                                    $subject = $row['notice_subject'];
                                    $notice = $row['notice'];
                                    ?> 
                                    <li class="media" id="noticescroll">
                                        <div class="mr-3">
                                            <i class="fa fa-arrow-right"></i>
                                        </div>

                                        <div class="media-body">
                                            <a href="#"><?php echo $subject; ?>.</a>
                                            <span class="text-muted font-size-sm" style="text-align: right;">(<?php echo date("d-m-Y h:i:sa", $row['entry_time']); ?>)</span> <br>
                                            <b><?php echo $notice; ?></b>											
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul><br>
                        </div>
                    </marquee>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6 flex-fill">
                <div class="card flex-fill" >
                    <div class="card-body" >
                        <h5 class="card-title">Upcoming birthdays</h5>
                    <div style="overflow-y: scroll;height: 500px;">
                        <?php
                        $month = date('m') - 1;
                        $year = date('Y');
                        $query = $conn->query("select *  from employee_profile INNER JOIN employee on employee.e_id = employee_profile.e_id WHERE employee.employee_status = 1 AND employee_profile.admin_id = '" . $_SESSION['admin_id'] . "' ORDER BY employee_profile.date_of_birth ASC ");
                        while ($row = $query->fetch_assoc()) { 
                            $d = strtotime($row['date_of_birth']);
                            $date = date("d/m/Y", $d);
                            $birthmonth = date("m", $d);
                            $day = date("d", $d);
                            $currentday = date("d");
                            $update = $currentday + 7;
                            if($birthmonth === date('m') && $day >= $currentday && $day <= $update){
                                $currentDay = false;
                                if($day === $currentday){
                                    $current = true;
                                }
                        ?>
                            <div class="leave-info-box">
                                <div class="media align-items-center">
                                    <div class="text-sm my-0"><?php echo $row['e_firstname'] . " " . $row['e_lastname']; ?></div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <span class="text-sm text-dark"><?php echo $date; ?></span>
                                    </div>
                                    <div class="col-6 text-right">
                                <?php
                                        if($current){
                                        ?>
                                            <span class="badge bg-inverse-success">Today</span>
                                <?php
                                        }else{
                                            
                                        ?>
                                            <span class="badge bg-inverse-purple">upcoming</span>
                                <?php } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        } ?>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h5 class="dash-title"><b>Your  Break &nbsp; </b></h5>
                            <div id="break_data" style="overflow-y: scroll;height: 500px;">

                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h5 class="dash-title"><b>Your  Remaining Leaves:&nbsp; </b><span class="text-right" ><b id="r_leave"></b></span></h5>

                        <div class="time-list">
                            <div class="dash-stats-list">
                                <?php
                                $months = date("m");
                                $years = date("Y");
                                date_default_timezone_set("Asia/Kolkata");
                                $monthName = date("F", mktime(0, 0, 0, $months));

                                $fromdt = strtotime("First Day Of  $monthName $years");
                                $todt = strtotime("Last Day of $monthName $years");
                                $leave = mysqli_query($conn, "SELECT * FROM leaves where e_id = " . $_SESSION['e_id'] . " and from_date between '$fromdt' and '$todt' ");
                                $count = mysqli_num_rows($leave);
                                ?>
                                <h4><?php echo $count; ?></h4>
                                <p>Leave Taken In this month</p>
                            </div>
                        </div>
                        <div class="request-btn">
                            <a class="btn btn-primary" onclick="remaining_leave()" href="#" data-toggle="modal" data-target="#add_emp_leave" >Apply Leave</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 flex-fill">
                <div class="card ">
                    <div class="card-body">
                        <div class="dash-sidebar">
                            <h5 class="dash-title">Upcoming Holiday</h5>
                            <?php
                            $date = strtotime(date("Y-m-d"));
                            $sql = mysqli_query($conn, "SELECT * From holidays where holiday_date > '$date' and admin_id = " . $_SESSION['admin_id'] . " ");
                            $no = 1;
                            while ($row = mysqli_fetch_array($sql)) {
                                if($no < 1){
                                ?>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="holiday-title mb-0"><?php
                    $h_date = date("d F Y", $row['holiday_date']);
                    echo $row['holiday_name'] . " " . $h_date;
                                ?></h4>
                                    </div>
                                </div>
                                <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
        <?php include 'footer.php'; ?>
        <div id="add_emp_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="leave_id" id="leave_id">
                                <option value="">Select Leave Type</option>
                                <?php
                                $sql = mysqli_query($conn, "SELECT * FROM add_leave where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    ?>
                                    <option value="<?php echo $row['leave_id'] ?>"><?php echo $row['leave_type']; ?></option>
                                    <?php
                                }
                                ?>	
                            </select>
                        </div>
                        <div class="form-group">
                            <label>From <span class="text-danger">*</span></label>
                            <input type="date" id="from_date" name="from_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>To <span class="text-danger">*</span></label>
                            <input type="date" id="to_date" onchange="count_leave()" name="to_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Total Days <span class="text-danger">*</span></label>
                            <input type="text" id="total_days" name="total_days" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Remaining Leaves <span class="text-danger">*</span></label>
                            <input class="form-control" readonly type="text" name="remaining_leave" id="remaining_leave">
                            <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                            <input type="hidden" name="e_id" id="e_id" value="<?php echo $_SESSION['e_id']; ?>" >
                        </div>
                        <div class="form-group">
                            <label>Leave Reason <span class="text-danger">*</span></label>
                            <textarea rows="4" class="form-control" name="leave_reason" id="leave_reason"></textarea>
                        </div>
                        <div class="submit-section">
                            <input type="submit" name="insert" value="insert" onclick="add_leave()" id="insert" class="btn btn-primary submit-btn" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gretings From Windson Payroll</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
              <div class="modal-body" >
                    <center>
                        <h3 >Windson Payroll wishing you</h3>
                        <h2 style="color:black">Happy Birthday..</h2><br>
                        <img src="https://miro.medium.com/max/1078/1*GG1jO9P45J9oP_oAvTWjug.png" height="70%" width="70%">
                    </center>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">

            $(document).ready(function () {
            var birth = $("#birth_status").val();
                if(localStorage.getItem('birthdate') != new Date()){
                    if(birth == 'yes'){
                        localStorage.setItem('birthdate', new Date());
                        $(".bd-example-modal-lg").modal('show');
                    }
                }
                
                att_change();
                remaining_leave();
                show();

            });
            // break chart
            function show() {
                var e_id = $("#e_id").val();
                var admin_id = $("#admin_id").val();
                $.ajax({
                    url: 'fetch.php',
                    type: 'POST',
                    data: {e_id: e_id, admin_id: admin_id, action: 'break_time'},
                    success: function (data)
                    {
                        $('#break_info').html(data);
                        chartdata();
                    }
                });
            }

            // request employee function here
            function add_leave() {

                var leave_id = document.getElementById('leave_id').value;
                var from_date = document.getElementById('from_date').value;
                var to_date = document.getElementById('to_date').value;
                var total_days = document.getElementById('total_days').value;
                var leave_reason = document.getElementById('leave_reason').value;
                var admin_id = document.getElementById('admin_id').value;
                var e_id = document.getElementById('e_id').value;

                if (val_leave_id(leave_id)) {
                    if (val_from_date(from_date)) {
                        if (val_to_date(to_date)) {
                            if (val_leave_reason(leave_reason)) {
                                $.ajax({
                                    url: "../control.php",
                                    method: "POST",
                                    data: {leave_id: leave_id,
                                        from_date: from_date,
                                        to_date: to_date,
                                        leave_reason: leave_reason,
                                        total_days: total_days,
                                        admin_id: admin_id,
                                        e_id: e_id,
                                        action: "request_leave"},
                                    datatype: "html",
                                    success: function (data)
                                    {
                                        $('#add_emp_leave').modal('hide');
                                        window.location.href = 'employee-dashboard.php';
                                    }
                                });
                            }
                        }
                    }
                }
            }

            function val_leave_id(val)
            {
                if (val == '') {
                    alert("Please Select Leave Type");
                    return false;
                } else {
                    return true;
                }
            }
            function val_from_date(val)
            {
                if (val == '') {
                    alert("Please Select From Date");
                    return false;
                } else {
                    return true;
                }
            }
            function val_to_date(val)
            {
                if (val == '') {
                    alert("Please Select To Date");
                    return false;
                } else {
                    return true;
                }
            }
            function val_leave_reason(val)
            {
                if (val == '') {
                    alert("Please Write Your Leave Reason");
                    return false;
                } else {
                    return true;
                }
            }

            function count_leave()
            {
                var from_date = document.getElementById('from_date').value;
                var to_date = document.getElementById('to_date').value;
                var date = new Date(from_date);
                var date1 = new Date(to_date);
                const ONE_DAY = 1000 * 60 * 60 * 24;
                var days = Math.abs(date - date1);
                const diff = Math.round(days / ONE_DAY);
                document.getElementById('total_days').value = diff + 1;
            }
            function remaining_leave() {
                var e_id = document.getElementById('e_id').value;
                $.ajax({
                    url: "fetch.php",
                    method: "POST",
                    data: {e_id: e_id, action: "show_remaining_leave"},
                    datatype: "html",
                    success: function (data)
                    {
                        $('#remaining_leave').val(data);
                        $('#r_leave').html(data);
                    }
                });
            }
            function att_change()
            {
                var day = '<?php echo date('Y-m-d'); ?>';
                var admin_id = $("#admin_id").val();
                var e_id = $("#e_id").val();
                $.ajax({
                    url:"fetch.php",
                    type:"POST",
                    data:{day:day,admin_id:admin_id,e_id:e_id,action:'att_info'},
                    success:function(data)
                    {
                        var json = JSON.parse(data);
                        $("#break_data").html(json.break_time)
                    }
                });
            }
        </script>

        <?php

} else {
    header("Location:../index.php");
}
?>