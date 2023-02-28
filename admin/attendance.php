<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Attendance';
    include 'admin_header.php';
    ?>
    <style>
        /*th:first-child, td:first-child {*/
        /*    position: sticky;*/
        /*    left: 0px;*/
        /*    background-color: whitesmoke;*/
        /*}*/
    </style>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
                <div class="col-auto float-right ml-auto">
                    <a href="#" data-toggle="modal" data-target="#add_attendance" class="btn btn-info btn-sm"><i
                                class="fa fa-plus-circle"></i> Add Attendance</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
<style>
            .DTFC_LeftBodyLiner {
            overflow-x: hidden;
            }
        </style>
    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <select onchange="month_change()" id="month" class="form-control">
                    <option value="<?php echo date('n'); ?>">Select Month</option>
                    <option value="1">Jan</option>
                    <option value="2">Feb</option>
                    <option value="3">Mar</option>
                    <option value="4">Apr</option>
                    <option value="5">May</option>
                    <option value="6">Jun</option>
                    <option value="7">Jul</option>
                    <option value="8">Aug</option>
                    <option value="9">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>

            </div>
        </div>
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <select id="year" onchange="year_change()" class="form-control">
                    <option value="<?php echo date('Y'); ?>">Select Year</option>
                    <?php
                    for ($i = 2019; $i <= 2030; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>
        <div class="col-sm-6 col-md-5">
            <div class="form-group">
                <label class="form-control"><b>Attendance Report Month : </b><b><span id="month_year"
                                                                                      style="color: black;font-size: 16px"></span></b></label>
            </div>
        </div>
    </div>
    <!-- /Search Filter -->

    <div class="row">
        <div class="col-md-12">
            <div id="view_data">

            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
    <div id="add_attendance" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Staff<span class="text-danger"></span></label>
                                <select class="form-control" id="card_id" name="card_id" multiple>
                                    <?php
                                    //show employees on add salary form from employees table
                                    $query = mysqli_query($conn, "select * from employee where admin_id = " . $_SESSION['admin_id'] . " and employee_status = 1 ");
                                    echo "<option disabled='' selected=''>Select Employees</option>";
                                    while ($selectstaff = mysqli_fetch_array($query)) {
                                        echo "<option value='" . $selectstaff['emp_cardid'] . "'>" . $selectstaff['e_firstname'] . " " . $selectstaff['e_lastname'] . "</option>";
                                        $empcardid = $selectstaff['emp_cardid'];
                                        $e_id = $selectstaff['e_id'];
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Date<span class="text-danger"></span></label>
                                <input type="date" class="form-control" id="date" name="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Date<span class="text-danger"></span></label>
                                <select class="form-control" id="present_status" name="present_status">
                                    <option value="Full" selected=''>FULL DAY</option>
                                    <option value="Half">HALF DAY</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" onclick="addattendance()" name="submit" id="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Data -->
    <div id="add_attendance_employee" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Selected Employee<span class="text-danger"></span></label>
                                <input type="text" disabled="" class="form-control" id="name" name="name">
                                <input type="hidden" id="empcardid" name="empcardid">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select Staff<span class="text-danger"></span></label>
                                <select class="form-control" id="present_type" name="present_type">
                                    <option selected value="Full">FULL</option>
                                    <option value="Half">HALF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Selected Date<span class="text-danger"></span></label>
                                <input type="text" class="form-control" disabled="" id="adate" name="adate">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" onclick="add_attendance()" name="submit" id="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Data -->

    <!-- Attendance Modal -->
    <div class="modal custom-modal fade" role="dialog" id="att_info">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card punch-status">
                                <div class="card-body">
                                    <h5 class="card-title">Timesheet <small class="text-muted" id="date_view"></small>
                                    </h5>
                                    <div class="punch-det" id="punch_date">

                                    </div>
                                    <div class="statistics">
                                        <div class="row" id="total_break">
                                        </div>
                                    </div>
                                    <div class="statistics">
                                        <div class="row">
                                            <div style="padding-top: 20px">
                                            </div>
                                            <div class="col-md-12">
                                                <select id="present_typee" class="form-control" name="present_typee">
                                                    <option value="Full">FULL DAY</option>
                                                    <option value="Half">HALF DAY</option>
                                                    <option value="Absent">ABSENT</option>
                                                </select>
                                            </div>
                                            <div style="padding-top: 20px">
                                            </div>
                                            <div class="col-md-12">
                                                <center><input type="submit" class="btn btn-info btn-sm" value="Change"
                                                               onclick="editattendance()" id="change" name="change">
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card recent-activity">
                                <div class="card-body">
                                    <h5 class="card-title">Activity</h5>
                                    <ul id="break_data" class="res-activity-list">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Attendance Modal -->
    <script type="text/javascript">
        $(document).ready(function () {
            month_change();
        });

        $(document).on('click', '.add_attendance', function () {
            var user_id = $(this).attr("id");
            var month = $("#month").val();
            var year = $("#year").val();
            var res = user_id.split(",");
            if (res[2] < 9) {
                res[2] = '0' + res[2];
            }
            if (month < 9) {
                month = '0' + month;
            }
            $("#empcardid").val(res[0]);
            $("#name").val(res[1]);
            $("#adate").val(month + '/' + res[2] + '/' + year);
            $("#add_attendance_employee").modal('show');
        });

        function month_change() {
            var month = $("#month").val();
            var year = $("#year").val();
            var admin_id = $("#admin_id").val();
            var month_names = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
            $("#overlay").show();
            $.ajax({
                url: "data.php",
                type: "POST",
                data: {month: month, year: year, admin_id: admin_id},
                success: function (data) {
                    $("#overlay").hide();
                    $('#view_data').html(data);
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                    datatable();

                }
            });
        }

        function year_change() {
            var year = $("#year").val();
            var month = $("#month").val();
            var admin_id = $("#admin_id").val();
            var month_names = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
            $("#overlay").show();
            $.ajax({
                url: "data.php",
                type: "POST",
                data: {month: month, year: year, admin_id: admin_id},
                success: function (data) {
                    $('#view_data').html(data);
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                    datatable();
                    $("#overlay").hide();
                }
            });
        }

        function addattendance() {
            var emp_cardno = $("#card_id").val();
            var date = $("#date").val();
            var admin_id = $("#admin_id").val();
            var present_status = $("#present_status").val();
            if (val_employee(emp_cardno)) {
                if (val_date(date)) {
                    if (val_present_status(present_type)) {
                        $("#overlay").show();
                        $.ajax({
                            url: "insert.php",
                            type: "POST",
                            data: {
                                emp_cardno: emp_cardno,
                                admin_id: admin_id,
                                present_status: present_status,
                                date: date,
                                action: 'add_attendance'
                            },
                            success: function (data) {
                                $("#add_attendance").modal("hide");
                                swal(data,'','success');
                                month_change();
                            }
                        });
                    }
                }
            }
        }

        function add_attendance() {
            var emp_cardno = $("#empcardid").val();
            var date = $("#adate").val();
            var present_type = $("#present_type").val();
            var admin_id = $("#admin_id").val();
            if (val_employee(emp_cardno)) {
                if (val_date(date)) {
                    if (val_present_status(present_type)) {
                        $("#overlay").show();
                        $.ajax({
                            url: "insert.php",
                            type: "POST",
                            data: {
                                emp_cardno: emp_cardno,
                                present_status: present_type,
                                admin_id: admin_id,
                                date: date,
                                action: 'add_attendance'
                            },
                            success: function (data) {
                                $("#empcardid").val('');
                                $("#adate").val('');
                                $("#add_attendance_employee").modal("hide");
                                swal(data,'','success');
                                month_change();
                            }
                        });
                    }
                }
            }
        }

        function val_employee(val) {
            if (val == '') {
                swal('Please Select Employee','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_date(val) {
            if (val == '') {
                swal('Please Select Date','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_present_status(val) {
            if (val == '') {
                swal('Please Select Attendance Type','','info');
                return false;
            } else {
                return true;
            }
        }

        function editattendance() {
            var present_type = $("#present_typee").val();
            var id = $("#at_id").val();
            $("#overlay").show();
            $.ajax({
                url: "update.php",
                type: "POST",
                data: {id: id, present_type: present_type, action: 'edit_attendance'},
                success: function (data) {
                    $("#att_info").modal("hide");
                    $("#overlay").hide();
                    swal(data,'','success');
                    month_change();
                }
            });
        }

        function attendance_info(id) {
            var admin_id = $("#admin_id").val();
            $("#att_info").modal('show');
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {id: id, admin_id: admin_id, action: "attendance_fetch"},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    $("#date_view").html(json_data[3]);
                    $("#punch_date").html(json_data[0]);
                    $("#total_break").html(json_data[2]);
                    $("#break_data").html(json_data[1]);
                    $("#overlay").hide();
                }
            });
        }

        //
        function datatable() {
            var table = $('#attendance').DataTable({
                scrollY: '400px',
                scrollX: 'auto',
                ordering: true,
                scroller: false,
                pageLength: 100,
                fixedColumns: true,
            });
        }
    </script>

    <?php
} else {
    header("Location:../index.php");
}

