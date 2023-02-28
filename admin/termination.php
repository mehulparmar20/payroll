<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include 'admin_header.php';
    ?>
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Termination</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Termination</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_termination"><i
                                class="fa fa-plus-circle"></i> Add Termination</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div id="show_detail">

                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    <?php include 'footer.php'; ?>
    <!-- Add Termination Modal -->
    <div id="add_termination" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Termination</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Name <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="manager_name" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Designation <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="manager_designation" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Terminated Employee<span class="text-danger"></span></label>
                                <select class="form-control" id="e_id" name="e_id">
                                    <option value="error">--- select employee ---</option>
                                    <?php
                                    //show employees on add salary form from employees table
                                    $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname'] ?></option>
                                    <?php } ?>
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Termination Type <span class="text-danger">*</span></label>
                                <div class="add-group-btn">
                                    <select class="form-control" name="termination_type" id="termination_type">
                                        <option value="Misconduct">Misconduct</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Termination Date <span class="text-danger">*</span></label>

                                <input type="date" name="termination_date" id="termination_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Application Date <span class="text-danger">*</span></label>
                                <div>
                                    <input type="date" disabled name="notice_date" id="notice_date"
                                           value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="4" name="reason" id="reason"></textarea>
                                <input type="hidden" name="admin_id" id="admin_id"
                                       value="<?php echo $_SESSION['admin_id'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button id="submit" name="submit" onclick="termination()"
                                class="btn btn-primary submit-btn">Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Termination Modal -->

    <!-- Edit Termination Modal -->
    <div id="edit_termination" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Termination</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Name <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="tmanager_name" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Designation <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="tmanager_designation" value=""
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Terminated Employee<span class="text-danger"></span></label>
                                <select class="form-control" id="te_id" name="te_id">
                                    <option value="error">--- select employee ---</option>
                                    <?php
                                    //show employees on add salary form from employees table
                                    $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname'] ?></option>
                                    <?php } ?>
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Termination Type <span class="text-danger">*</span></label>
                                <div class="add-group-btn">
                                    <select class="form-control" name="ttermination_type" id="ttermination_type">
                                        <option value="Misconduct">Misconduct</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Termination Date <span class="text-danger">*</span></label>
                                <div>
                                    <input type="date" name="ttermination_date" value="" id="ttermination_date"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Application Date <span class="text-danger">*</span></label>
                                <div>
                                    <input type="text" disabled name="tnotice_date" id="tnotice_date"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="4" name="treason" id="treason"></textarea>
                                <input type="hidden" name="admin_id" id="admin_id"
                                       value="<?php echo $_SESSION['admin_id'] ?>">
                                <input type="hidden" name="termination_id" id="ttermination_id">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" onclick="edit_termination()" id="edit" name="edit">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Termination Modal -->

    <script>
        $(document).ready(function () {
            view_data();
        });

        function view_data() {
            $.ajax({
                url: '../control.php',
                type: 'POST',
                data: {action: 'show_termination'},
                success: function (data) {
                    $('#show_detail').html(data);
                    datatable();
                }
            });
        }

        // fetch edit data
        $(document).on('click', '.edit_data', function () {
            var termination_id = $(this).attr("id");
            $.ajax({
                url: "../control.php",
                method: "POST",
                data: {termination_id: termination_id, action: 'fetch_termination'},
                dataType: "json",
                success: function (data) {
                    $('#ttermination_id').val(data.termination_id);
                    $("#te_id").val(data.e_id);
                    $("#ttermination_type").val(data.termination_type);
                    $("#ttermination_date").val(data.termination_date);
                    $("#treason").val(data.reason);
                    $("#tnotice_date").val(data.notice_date);
                    $("#tmanager_name").val(data.manager_name);
                    $("#tmanager_designation").val(data.manager_designation);
                    $('#edit_termination').modal('show');
                }
            });
        });

        // update function
        function edit_termination() {
            var e_id = $('#te_id').val();
            var termination_type = $('#ttermination_type').val();
            var termination_date = $('#ttermination_date').val();
            var reason = $('#treason').val();
            var manager_name = $('#tmanager_name').val();
            var manager_designation = $('#tmanager_designation').val();
            var notice_date = $('#tnotice_date').val();
            var admin_id = $('#admin_id').val();
            var termination_id = $('#ttermination_id').val();
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_termination_type(termination_type)) {
                            if (val_termination_date(termination_date)) {
                                if (val_reason(reason)) {
                                    if (val_notice_date(notice_date)) {
                                        $.ajax({
                                            url: '../control.php',
                                            type: 'post',
                                            data: {
                                                e_id: e_id,
                                                termination_type: termination_type,
                                                termination_date: termination_date,
                                                reason: reason,
                                                manager_name: manager_name,
                                                manager_designation: manager_designation,
                                                notice_date: notice_date,
                                                admin_id: admin_id,
                                                termination_id: termination_id,
                                                action: 'edit_termination'
                                            },
                                            success: function (data) {
                                                swal("Edit Successfully.");
                                                $('#edit_termination').modal('hide');
                                                view_data();
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }

            function val_e_id(val) {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }

            function val_termination_type(val) {
                if (val == '') {
                    swal('Please Select Termination Type');
                    return false;
                } else {
                    return true;
                }
            }

            function val_termination_date(val) {
                if (val == '') {
                    swal('Please Select Termination Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_reason(val) {
                if (val == '') {
                    swal('Please Write Your Reason');
                    return false;
                } else {
                    return true;
                }
            }

            function val_notice_date(val) {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_name(val) {
                if (val == '') {
                    swal('Please Write an Manager Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val) {
                if (val == '') {
                    swal('Please Write an Manager Designation');
                    return false;
                } else {
                    return true;
                }
            }

        }

        // insert function
        function termination() {
            var e_id = $('#e_id').val();
            var termination_type = $('#termination_type').val();
            var termination_date = $('#termination_date').val();
            var reason = $('#reason').val();
            var manager_name = $('#manager_name').val();
            var manager_designation = $('#manager_designation').val();
            var notice_date = $('#notice_date').val();
            var admin_id = $('#admin_id').val();
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_termination_type(termination_type)) {
                            if (val_termination_date(termination_date)) {
                                if (val_reason(reason)) {
                                    if (val_notice_date(notice_date)) {
                                        $.ajax({
                                            url: '../control.php',
                                            type: 'post',
                                            data: {
                                                e_id: e_id,
                                                termination_type: termination_type,
                                                termination_date: termination_date,
                                                reason: reason,
                                                notice_date: notice_date,
                                                manager_name: manager_name,
                                                manager_designation: manager_designation,
                                                admin_id: admin_id,
                                                action: 'add_termination'
                                            },
                                            success: function (data) {
                                                swal("Termination Added Successfully.");
                                                $('#add_termination').modal('hide');
                                                view_data();
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }

            function val_manager_name(val) {
                if (val == '') {
                    swal('Please Write an Manager Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val) {
                if (val == '') {
                    swal('Please Write an Manager Designation');
                    return false;
                } else {
                    return true;
                }
            }

            function val_e_id(val) {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }

            function val_termination_type(val) {
                if (val == '') {
                    swal('Please Select Termination Type');
                    return false;
                } else {
                    return true;
                }
            }

            function val_termination_date(val) {
                if (val == '') {
                    swal('Please Select Termination Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_reason(val) {
                if (val == '') {
                    swal('Please Write Your Reason');
                    return false;
                } else {
                    return true;
                }
            }

            function val_notice_date(val) {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }

        }

        // delete data
        $(document).on('click', '.delete_data', function () {
            var termination_id = $(this).attr("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this record!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '../control.php',
                            type: 'POST',
                            data: {
                                id: termination_id,
                                action: 'delete_termination'
                            },
                            success: function (data) {
                                swal("Record Delete successfully");
                                view_data();
                            }
                        });
                    } else {
                        swal("Your Recorded is safe!");
                    }
                });
        });

        // Datatable
        function datatable() {
            var table = $('#termination').DataTable({
                // ordering:false
            });
        }

        function add() {
            document.getElementById('add').style.display = "block";
        }

        //    $(".form_datetime").datetimepicker1({
        //            format: "dd MM yyyy "
        //            });
    </script>
    <?php
} else {
    header("Location:../index.php");
}
?>