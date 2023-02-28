<?php
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
                    <h3 class="page-title">Leave Type</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Type</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn btn-info btn-sm" id="add" name="add" data-toggle="modal" data-target="#add_leavetype"
                       data-target="#add_leavetype"><i class="fa fa-plus-circle"></i> Add Leave Type</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div id="employee_table">
                    <table id="leaves" class="table table-striped custom-table mb-0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Leave Type</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- leave detail fetch here -->
                        <?php
                        $result = mysqli_query($conn, "select * from add_leave where admin_id = " . $_SESSION['admin_id'] . " ");
                        $no = 1;
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['leave_type']; ?></td>
                                <td class="text-right">
                                    <a href="#" class="edit_data" id="<?php echo $row['leave_id']; ?>"><i
                                                class="fa fa-pencil" aria-hidden="true"
                                                style=" color:black; font-size:15px;"></i></a>&nbsp;&nbsp;
                                    <a href="#" class="delete_data" id="<?php echo $row['leave_id']; ?>"><i
                                                class="fa fa-trash-o" aria-hidden="true"
                                                style="color:black; font-size:15px;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                        <?php } ?>
                        <!-- Leave Data END Here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- /Page Wrapper -->

    <?php include 'footer.php'; ?>

<!-- Add or edit data model here  -->
<div id="add_leavetype" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Leave Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Leave Type <span class="text-danger">*</span></label>
                    <input class="form-control" name="leave_type" id="leave_type" value="" type="text">
                </div>
                <div class="submit-section">
                    <!-- leave_id passed here's -->
                    <input type="hidden" name="leave_id" id="leave_id">
                    <input class="btn btn-primary submit-btn" onclick="leave_add()" type="submit" name="insert"
                           value="insert" id="insert">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- edit data model here  -->
<div id="edit_leavetype" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Leave Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="insert_form">
                    <div class="form-group">
                        <label>Leave Type <span class="text-danger">*</span></label>
                        <input class="form-control" name="eleave_type" id="eleave_type" value="" type="text">
                    </div>
                    <div class="submit-section">
                        <!-- leave_id passed here's -->
                        <input type="hidden" name="leave_id" id="eleave_id">
                        <input class="btn btn-primary submit-btn" type="submit" onclick="leave_edit()" name="insert"
                               value="Edit" id="insert">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Delete Leavetype Modal -->
<div class="modal custom-modal fade" id="delete_leavetype" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Leave Type</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="del_leave()" class="btn btn-primary continue-btn">Delete</a>
                            <input type="hidden" value="" id="del_val">
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal"
                               class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Leavetype Modal -->
<script type="text/javascript">
    $(document).ready(function () {
        datatable();
    });
    $(document).on('click', '.edit_data', function () {
        var leave_id = $(this).attr("id");
        $.ajax({
            url: "read.php",
            method: "POST",
            data: {leave_id: leave_id, action: 'leave_fetch'},
            datatype: "html",
            success: function (data) {
                var user = JSON.parse(data);
                $('#eleave_type').val(user.leave_type);
                $('#leave_id').val(user.leave_id);
                $('#edit_leavetype').modal('show');
            }
        });
    });

    // function for insert and edit data

    function datatable() {
        $("#leaves").dataTable({
            ordering: false
        });
    }

    function leave_add() {
        var leave_type = $('#leave_type').val();
        var admin_id = $('#admin_id').val();
        if (val_laeave_type(leave_type)) {
            $.ajax({
                url: "insert.php",
                method: "post",
                data: {
                    leave_type: leave_type,
                    admin_id: admin_id,
                    action: 'leave_insert'
                },
                success: function (data) {
                    $("#add_leavetype").modal("hide");
                    swal("Leave TYpe Added");
                    window.location.href = 'leave_type.php';
                }
            });
        }
    }

    function leave_edit() {
        var leave_type = $('#eleave_type').val();
        var leave_id = $('#leave_id').val();
        if (val_laeave_type(leave_type)) {
            $.ajax({
                url: "update.php",
                method: "post",
                data: {
                    leave_type: leave_type,
                    leave_id: leave_id,
                    action: 'leave_edit'
                },
                success: function (data) {
                    $("#add_leavetype").modal("hide");
                    swal("Leave Edited Successfully.");
                    window.location.href = 'leave_type.php';
                }
            });
        }
    }

    function val_laeave_type(val) {
        if (val == '') {
            swal({title:'Please Enter Leave Type.'});
            return false;
        } else {
            return true;
        }
    }


    $(document).on('click', '.delete_data', function () {
        var leave_id = $(this).attr("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "delete.php",
                        method: "POST",
                        data: {leave_id: leave_id, action: 'leavetype_delete'},
                        datatype: "text",
                        success: function (data) {
                            swal("Leave Type Delete Successfully.");
                            window.location.href = 'leave_type.php';
                        }
                    });
                } else {
                    swal("Your record is safe!");
                }
            });
    });

</script>

<?php
}else{
    header("location:../index.php");
}
