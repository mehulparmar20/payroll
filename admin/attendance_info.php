<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = "Attendance Details";
    include 'admin_header.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <input type="date" onchange="new_change()" id="search_date" class="form-control">
                <input type="hidden" value="<?php echo date("m/d/Y"); ?>" id="c_date" class="form-control">
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <label class="form-control"><b>Break Info Month : </b><b><span id="date_view"
                                                                               style="color: black;font-size: 18px"></span></b></label>
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

    <!-- edit Attendance Modal -->
    <div id="edit_attendance" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Employee Name <span class="text-danger">*</span></label>
                                <input class="form-control" disabled id="emp_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Employee Card No <span class="text-danger">*</span></label>
                                <input disabled class="form-control" id="emp_cardno" type="text">
                                <input class="form-control" id="user_id" type="hidden">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Punch In </label>
                                <input class="form-control" id="in_time" disabled="" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Status </label>
                                <input class="form-control" id="status" disabled="" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Late Time </label>
                                <input class="form-control" disabled="" id="late_time" type="text">
                                <input class="form-control" disabled="" id="att_id" type="hidden">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fine </label>
                                <input class="form-control" id="fine" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Attendance Status Day</label>
                                <select id="a_status" class="form-control" name="a_status">
                                    <option value="Full">Full</option>
                                    <option value="Half">Half</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" value="Edit Attendance" onclick="att_edit()"
                               class="btn btn-primary submit-btn">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /edit Attendance Modal -->
    <!-- Delete Employee Modal -->
    <div class="modal custom-modal fade" id="delete_attendance" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Attendance</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="del_attendance()"
                                   class="btn btn-primary continue-btn">Delete</a>
                                <input type="hidden" value="" id="del_val">
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Employee Modal -->

    <script type="text/javascript">
        $(document).ready(function () {
            c_change();

        });
        $(document).on('click', '.edit', function () {
            var atr_id = $(this).attr("id");
            $.ajax({
                url: 'read.php',
                method: 'POST',
                dataType: 'json',
                data: {atr_id: atr_id, action: 'fetch_attendance'},
                success: function (data) {
                    $('#att_id').val(data.Attandance_id);
                    $('#emp_name').val(data.emp_name);
                    $('#emp_cardno').val(data.employee_cardno);
                    $('#in_time').val(data.in_time);
                    $('#status').val(data.present_status);
                    $('#late_time').val(data.late_time + " Min");
                    $('#fine').val(data.fine);
                    $('#a_status').val(data.attendance_status);
                    $("#edit_attendance").modal('show');
                }
            });
        });

        function c_change() {
            var day = $("#c_date").val();
            var admin_id = $("#admin_id").val();
            $("#date_view").html(day);
            datatable('attendance', 'des');
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {day: day, admin_id: admin_id, action: 'attendance_info'},
                success: function (data) {
                    $('#view_data').html(data);
                    datatable('attendance', 'no');
                    $("#overlay").hide();
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                }
            });
        }

        function att_edit() {
            var id = $('#att_id').val();
            var fine = $('#fine').val();
            var a_status = $('#a_status').val();
            $("#overlay").show();
            $.ajax({
                url: "update.php",
                type: "POST",
                data: {fine: fine, a_status: a_status, id: id, action: 'attendance_edit'},
                success: function (data) {
                    var day = $("#search_date").val();
                    if (day == '') {
                        c_change();
                    } else {
                        new_change();
                    }
                    datatable();
                    $("#overlay").hide();
                    $("#edit_attendance").modal('hide');
                }
            });
        }

        function new_change() {
            var day = $("#search_date").val();
            var admin_id = $("#admin_id").val();
            $("#date_view").html(day);
            $("#overlay").show();
            datatable('attendance', 'des');
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {day: day, admin_id: admin_id, action: 'attendance_info'},
                success: function (data) {
                    $('#view_data').html(data);
                    datatable('attendance', 'no');
                    $("#overlay").hide();
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                }
            });
        }

        // Break delete
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            $("#del_val").val(id);
            $("#delete_attendance").modal("show");
        });

        function del_attendance() {
            var id = $("#del_val").val();

            $.ajax({
                url: 'delete.php',
                type: 'post',
                data: {a_id: id, action: 'attendance_delete'},
                success: function (data) {
                    $("#delete_attendance").modal("hide");
                    swal("Attendance Delete successfully");
                    var day = $("#search_date").val();
                    if (day == '') {
                        c_change();
                    } else {
                        new_change();
                    }
                }
            });
        }

        // view delete
        function datatable(value, type) {
            if (type == "des") {
                if ($.fn.DataTable.isDataTable("#" + value)) {
                    $('#' + value).dataTable().fnClearTable();
                    $('#' + value).dataTable().fnDestroy();
                }
            } else {
                var table = $('#' + value).DataTable({
                    scrollY: '400px',
                    scrollX: 'auto',
                    ordering: true,
                    scroller: false,
                    pageLength: 100,
                    fixedColumns: true,
                });
                $(".DTFC_LeftBodyLiner").hide();
            }
        }

    </script>

    <?php
} else {
    header("Location:../index.php");
}
