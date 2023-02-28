<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Designations';
    include 'admin_header.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_designation"><i
                            class="fa fa-plus"></i> Add Designation</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <div class="row">
        <div class="col-md-12">
            <div>
                <table id="designation" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM designation INNER JOIN departments on designation.department_id = departments.departments_id  where designation.admin_id = " . $_SESSION['admin_id'] . " ");
                    $no = 1;
                    while ($row = mysqli_fetch_array($sql)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['designation_name'] ?></td>
                            <td><?php echo $row['departments_name'] ?></td>
                            <td class="text-right">
                                <a class="edit" href="#" id="<?php echo $row['designation_id'] ?>" data-toggle="modal"
                                   data-target="#edit_designation" title="Edit" style="color:black"><i
                                            class="fa fa-pencil m-r-5"></i> </a>
                                <?php if ($row['d_used_count'] > 0) { ?>
                                    <a href="#" id="<?php echo $row['designation_id'] ?>"
                                       title="This Data is Used in Other Place You Can not Delete This."
                                       style="color:black;cursor: not-allowed"><i class="fa fa-trash-o m-r-5"></i></a>
                                <?php } else { ?>
                                    <a class="delete" href="#" id="<?php echo $row['designation_id'] ?>" title="Delete"
                                       data-toggle="modal" data-target="#delete_designation" style="color:black"><i
                                                class="fa fa-trash-o m-r-5"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add Designation Modal -->
    <div id="add_designation" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Designation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Designation Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="designation_name" type="text">
                    </div>
                    <div class="form-group">
                        <label>Department <span class="text-danger">*</span></label>
                        <select id="department" class="form-control">
                            <option value="no">Select Department</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option value="<?php echo $row['departments_id'] . "," . $row['used_count']; ?>"><?php echo $row['departments_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="submit-section">
                        <input type="submit" onclick="add_des()" class="btn btn-primary submit-btn" name="submit" id="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Designation Modal -->

    <!-- Edit Designation Modal -->
    <div id="edit_designation" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Designation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Designation Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="d_name" type="text">
                        <input class="form-control" id="d_id" type="hidden">
                    </div>
                    <div class="form-group">
                        <label>Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="dp_name">
                            <option>Select Department</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option value="<?php echo $row['departments_id']; ?>"><?php echo $row['departments_name']; ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="count" value="<?php echo $row['used_count'] ?>">
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" name="submit" onclick="edit_designation()" value="Save"
                               class="btn btn-primary submit-btn">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Designation Modal -->
    <?php include 'footer.php'; ?>
    <script>
        $(document).ready(function () {
            datatable();
            $(document).on('click', '.edit', function () {
                var d_id = $(this).attr("id");
                $.ajax({
                    url: "read.php",
                    method: "POST",
                    data: {d_id: d_id, action: 'fetch_designation'},
                    dataType: "json",
                    success: function (data) {
                        $("#d_id").val(data.designation_id);
                        $("#d_name").val(data.designation_name);
                        $("#dp_name").val(data.department_id);
                        $('#edit_designation').modal('show');

                    }
                });
            });
        });
        function edit_designation() {
            var d_id = $('#d_id').val();
            var designation_name = $('#d_name').val();
            var department = $('#dp_name').val();
            var admin_id = $('#admin_id').val();
            var count = $('count').val();
            if (val_designation_name(designation_name)) {
                if (val_department(department)) {
                    $.ajax({
                        url: "../control.php",
                        type: "POST",
                        data: {
                            d_id: d_id,
                            designation_name: designation_name,
                            department: department,
                            admin_id: admin_id,
                            count: count,
                            action: 'edit_designation'
                        },
                        datatype: "html",
                        success: function (data) {
                            $('#add_designation').modal('hide');
                            window.location.href = "designations.php";
                        }
                    });
                }
            }
        }

        function add_des() {
            var designation_name = $('#designation_name').val();
            var department = $('#department').val();
            var admin_id = $('#admin_id').val();
            var count = $('#count').val();
            if (val_designation_name(designation_name)) {
                if (val_department(department)) {
                    $.ajax({
                        url: "../control.php",
                        type: "POST",
                        data: {
                            designation_name: designation_name,
                            department: department,
                            admin_id: admin_id,
                            count: count,
                            action: 'designation'
                        },
                        datatype: "html",
                        success: function (data) {
                            $('#add_designation').modal('hide');
                            window.location.href = 'designations.php';
                        }
                    });
                }
            }
        }

        function val_designation_name(val) {
            if (val == '') {
                swal({title:'Please Enter Designation'});
                return false;
            } else {
                return true;
            }
        }

        function val_department(val) {
            if (val == 'no') {
                swal({title:'Please Select Department'});
                return false;
            } else {
                return true;
            }
        }

        function datatable() {
            $('#designation').DataTable({
                paging: true,
                info: true,
                ordering: false
            });
        }

        // function for delete data
        $(document).on('click', '.delete', function () {
            var d_id = $(this).attr("id");
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
                            data: {d_id: d_id, action: 'designation_delete'},
                            success: function (data) {
                                swal("Deleted!", "Designation Delete successfully.", "success");
                                window.location.href = 'designations.php';
                            }
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });

        });
    </script>
    <?php
} else {
    header("Location:../index.php");
}
?>

<!--






-->