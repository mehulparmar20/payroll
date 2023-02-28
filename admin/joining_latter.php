<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include'admin_header.php';
    ?>					
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Joining</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Joining</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_experience"><i class="fa fa-plus-circle"></i> Add Joining</a>
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
    <!-- /Page Content -->
    <?php
    include 'footer.php';
}
?>
<!-- Add Termination Modal -->
<div id="add_experience" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Add Joining</b></h5>
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
                            <label>Employee <span class="text-danger">*</span></label>
                            <select class="form-control" id="e_id" onchange="joining_date()">
                                <option value="error">--- select employee ---</option>
                                <?php
                                //show employees on add salary form from employees table
                                $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <option value="<?php echo $row['e_id']; ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Employee Id <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="emp_id" disabled value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" disabled="" id="department" class="form-control">
                            <input type="hidden" name="department" disabled="" id="department_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Joining Date <span class="text-danger">*</span></label>
                            <div>
                                <input type="date" id="joining" class="form-control">
                                <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="submit-section" style="margin-left: 37% " >
                        <input type="submit" class="btn btn-primary submit-btn" onclick="add_joining()" id="insert" name="insert">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Termination Modal -->

<!-- edit Termination Modal -->
<div id="edit_experience" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Edit Joining</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Manager Name <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="jmanager_name" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Manager Designation <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="jmanager_designation" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Employee <span class="text-danger">*</span></label>
                            <select class="form-control" id="je_id" onchange="joining_date_2()" name="e_id">
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
                            <label>Employee Id <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="jemp_id" disabled value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <input type="text" name="jdepartment" disabled="" id="jdepartment" class="form-control">
                            <input type="hidden" name="jdepartment_id" disabled="" id="jdepartment_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Application Date <span class="text-danger">*</span></label>
                            <div>
                                <input type="date" name="application_date" id="japplication_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                                <input type="hidden" name="joining_id" id="jjoining_id">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section" style="margin-left: 37% " >
                        <input type="submit" onclick="edit_form()" class="btn btn-primary submit-btn" id="insert" name="insert">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Termination Modal -->

<script>
    function joining_date()
    {
        var e_id = $('#e_id').val();

        $.ajax({
            url: 'read.php',
            method: 'POST',
            data: {e_id: e_id, action: 'fetch_joining'},
            dataType: 'html',
            success: function (data)
            {
                var j = JSON.parse(data);
                $('#joining').val(j.join_date);
                $('#emp_id').val(j.emp_cardid);
                $('#department').val(j.departments_name);
                $('#department_id').val(j.departments_id);
            }
        });
    }

    function joining_date_2()
    {
        var e_id = $('#je_id').val();
        $.ajax({
            url: 'read.php',
            method: 'POST',
            data: {e_id: e_id, action: 'fetch_joining'},
            dataType: 'html',
            success: function (data)
            {
                var j = JSON.parse(data);
                $('#jjoining_date').val(j.join_date);
                $('#jemp_id').val(j.emp_cardid);
                $('#jdepartment').val(j.departments_name);
            }
        });
    }

    $(document).ready(function () {
        view_data();
    });
// View data
    function view_data()
    {
        $.ajax({
            url: 'read.php',
            type: 'POST',
            data: {action: 'show_joining'},
            success: function (data) {
                $('#show_detail').html(data);
                datatable();
            }
        });
    }
        // fetch edit data
        $(document).on('click', '.edit_data', function () {
            var joining_id = $(this).attr("id");
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {joining_id: joining_id, action: 'fetch_editjoining'},
                dataType: "json",
                success: function (data) {
                    $('#jjoining_id').val(data.joining_id);
                    $("#je_id").val(data.e_id);
                    $("#jdepartment_id").val(data.department_name);
                    $("#jdepartment").val(data.departments_name);
                    $("#jemp_id").val(data.emp_id);
                    $("#japplication_date").val(data.joining_date);
                    $("#jmanager_name").val(data.manager_name);
                    $("#jmanager_designation").val(data.manager_designation);
                    $("#edit_experience").modal('show');
                }
            });
        });

        // edit function
        function edit_form() {
            var e_id = $('#je_id').val();
            var department = $('#jdepartment_id').val();
            var emp_id = $('#jemp_id').val();
            var application_date = $('#japplication_date').val();
            var admin_id = $('#admin_id').val();
            var joining_id = $('#jjoining_id').val();
            var manager_name = $('#jmanager_name').val();
            var manager_designation = $('#jmanager_designation').val();
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_emp_id(emp_id)) {
                            if (val_department(department)) {
                                if (val_application_date(application_date)) {
                                    $.ajax({
                                        url: 'update.php',
                                        type: 'post',
                                        data: {
                                            e_id: e_id,
                                            emp_id: emp_id,
                                            department: department,
                                            application_date: application_date,
                                            admin_id: admin_id,
                                            joining_id: joining_id,
                                            manager_name: manager_name,
                                            manager_designation: manager_designation,
                                            action: 'edit_joining'},
                                        success: function (data) {
                                            swal("Success!", "Data Edit Successfully!", "success");
                                             $('#edit_experience').modal('hide');
                                             view_data();
                                        }
                                    });
                                }
                            }
                        }
                    }
                }
            }

            function val_e_id(val)
            {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }
            
            function val_emp_id(val)
            {
                if (val == 'error') {
                    swal('Please Select an Employee ID');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_name(val)
            {
                if (val == '') {
                    swal('Please Enter an Admin Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val)
            {
                if (val == '') {
                    swal('Please Enter an Admin Designation');
                    return false;
                } else {
                    return true;
                }
            }

            function val_department(val)
            {
                if (val == '') {
                    swal('Please Select Department');
                    return false;
                } else {
                    return true;
                }
            }

            function val_application_date(val)
            {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }
        }

        // insert function
        function add_joining() {
            var e_id = $('#e_id').val();
            var department = $('#department_id').val();
            var emp_id = $('#emp_id').val();
            var application_date = $('#joining').val();
            var admin_id = $('#admin_id').val();
            var manager_name = $('#manager_name').val();
            var manager_designation = $('#manager_designation').val();
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_emp_id(emp_id)) {
                            if (val_department(department)) {
                                if (val_application_date(application_date)) {
                                    $.ajax({
                                        url: 'insert.php',
                                        type: 'post',
                                        data: {e_id: e_id,
                                            emp_id: emp_id,
                                            department: department,
                                            application_date: application_date,
                                            admin_id: admin_id,
                                            manager_name: manager_name,
                                            manager_designation: manager_designation,
                                            action: 'add_joining'},
                                        success: function (data) {
                                            swal("Success!", "Data added Successfully!", "success");
                                            $('#add_experience').modal('hide');
                                            $('#department_id').val('');
                                            $('#department').val('');
                                            $('#emp_id').val('');
                                            $('#joining').val('');
                                            $('#e_id').val('');
                                            $('#manager_name').val('');
                                            $('#manager_designation').val('');
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

            function val_manager_name(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Designation');
                    return false;
                } else {
                    return true;
                }
            }

            function val_e_id(val)
            {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }

            function val_emp_id(val)
            {
                if (val == '') {
                    swal('Please Write Your Employee Id');
                    return false;
                } else {
                    return true;
                }
            }

            function val_department(val)
            {
                if (val == '') {
                    swal('Please Select Department');
                    return false;
                } else {
                    return true;
                }
            }

            function val_application_date(val)
            {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }

        

        // delete data
        $(document).on('click', '.delete_data', function () {
            var joining_id = $(this).attr("id");
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
                        url: 'delete.php',
                        type: 'POST',
                        data: {id: joining_id,
                               action: 'delete_joining'
                        },
                        success: function (data) {
                            swal("Success!", "Data Deleted Successfully!", "success");
                            view_data();
                        }
                    });
                } else {
                    swal("Your Recorded is safe!");
                }
            });
        });

    function datatable(){
        var table = $('#joining_data').DataTable({
            responsive: true,
            ordering: false
        });
    }

</script>