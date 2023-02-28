<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include'admin_header.php';
    ?>				
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Resignation Request</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resignation Request</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_resignation"><i class="fa fa-plus"></i> Add Resignation</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="leave" class="table table-striped custom-table mb-0 ">
                    <thead>
                        <tr>
                            <th><b>Employee</b></th>
                            <th><b>Resignation Date</b></th>
                            <th><b>Reason</b></th>
                            <th class="text-center"><b>Status</b></th>
                            <th class="text-right"><b>Actions</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, 'select * from resignation INNER JOIN employee on resignation.e_id = employee.e_id where resignation.admin_id = "' . $_SESSION['admin_id'] . '"');

                        while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#"><?php
                                            //     $q1 = mysqli_query($conn, "SELECT * FROM `employee` WHERE e_id = " . $row['e_id'] . "");
//                                                while($result = mysqli_fetch_array($q1))
//                                                {
                                            $f_name = $row['e_firstname'];
                                            $l_name = $row['e_lastname'];
//                                                }
                                            echo $f_name . " " . $l_name;
                                            ?></a>
                                    </h2>
                                </td>
                                <td><?php
                                    echo date("d-m-Y", $row['resignation_date']);
                                    ?></td>
                                <td><?php echo $row['reason']; ?></td>
                                <td class="text-center">
                                    <div class="menu-right">
                                        <?php if ($row['request_status'] == 00) { ?>
                                            <i class="fa fa-dot-circle-o text-purple"></i> New
                                        <?php } elseif ($row['request_status'] == 11) { ?>
                                            <i class="fa fa-dot-circle-o text-success"></i> Approved
                                        <?php } else { ?>
                                            <i class="fa fa-dot-circle-o text-danger"></i> Declined
                                        <?php } ?>
                                    </div>

                                </td>
                                <td class="text-right">
                                    <a class="approve" href="#" title="Approve" id="<?php echo $row['resignation_id']; ?>" style="color:green" ><i class="fa fa-check m-r-5"></i></a>
                                    <a class="decline" href="#" title="Decline" id="<?php echo $row['resignation_id']; ?>" style="color:red" ><i class="fa fa-times m-r-5"></i></a>
                                    <a class="delete" href="#" title="Delete" id="<?php echo $row['resignation_id']; ?>" style="color:black" ><i class="fa fa-trash-o m-r-5"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- /Page Content -->

    <?php include 'footer.php'; ?>
    <!-- Add Resignation Modal -->
    <div id="add_resignation" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Resignation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="insert_form" method="post">
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
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Select Staff<span class="text-danger"></span></label>
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
                                    <label>Notice Date <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="date" disabled class="form-control" value="<?php echo date("Y-m-d"); ?>" id="notice_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Resignation Date <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="date" id="resignation_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="4" name="reason" id="reason"></textarea>
                                    <input id="admin_id" type="hidden" value="<?php echo $_SESSION['admin_id']; ?>" >
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" name="insert" id="insert">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Resignation Modal -->
    <script>
        $(document).ready(function () {
    //                view_data();
            function view_emp(empname)
            {
                var admin_id = $('#admin_id').val();

                $.ajax({
                    url: 'fetch.php',
                    type: 'POST',
                    data: {emp_name: empname, admin_id: admin_id, action: 'emp_fetch'},
                    success: function (data)
                    {
                        console.log(data);
                        $('#view_data1').html(data);
                    }
                });
            }
            $('#leave').DataTable();
            $('#emp_search').keyup(function () {
                var search = $(this).val();
                view_emp(search);

            });
            $('input[name="date2"]').daterangepicker();
        });
        // function for delete data
        $(document).on('click', '.delete', function () {
            var resignation_id = $(this).attr("id");

            if (confirm('Are you sure to remove this record ?'))
            {
                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: {resignation_id: resignation_id, action: 'delete_resign'},
    //                   alert(are you Sure???);
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $("#" + resignation_id).remove();
                        alert("Record Deleted successfully");
                        window.location.href = 'resignation_data.php';
                    }
                });
            }
        });
        $(document).on('click', '.approve', function () {
            var resignation_id = $(this).attr("id");

            if (confirm('Are you sure want to approve for this leave?'))
            {
                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: {resignation_id: resignation_id, action: 'approve_resign'},
    //                   alert(are you Sure???);
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $("#" + resignation_id).remove();
    //                        alert(data);  
    //                        alert("Leave Approve successfully");
                        window.location.href = 'resignation_data.php';
                    }
                });
            }
        });

        $('#insert_form').on('submit', function (event) {
            event.preventDefault();

            var e_id = $('#e_id').val();
            var notice_date = $('#notice_date').val();
            var resignation_date = $('#resignation_date').val();
            var reason = $('#reason').val();
            var admin_id = $('#admin_id').val();
            var manager_name = $('#manager_name').val();
            var manager_designation = $('#manager_designation').val();

            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_resignation_date(resignation_date)) {
                            if (val_reason(reason)) {
                                $.ajax({
                                    url: '../control.php',
                                    method: 'post',
                                    data: {reason: reason,
                                        notice_date: notice_date,
                                        resignation_date: resignation_date,
                                        e_id: e_id,
                                        manager_name: manager_name,
                                        manager_designation: manager_designation,
                                        admin_id: admin_id,
                                        action: 'admin_add_resignation'},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#insert_form')[0].reset();
                                        $('#add_resignation').modal('hide');
                                        location.reload(true);
                                        resignation_show();
                                    }
                                });
                            }
                        }
                    }
                }
            }
        });
        function val_manager_name(val)
        {
            if (val == '') {
                alert('Please Write Manager Name');
                return false;
            } else {
                return true;
            }
        }

        function val_manager_designation(val)
        {
            if (val == '') {
                alert('Please Write Manager Designation');
                return false;
            } else {
                return true;
            }
        }
        function val_e_id(val)
        {
            if (val == 'error') {
                alert('Please Select an Employee');
                return false;
            } else {
                return true;
            }
        }

        function val_notice_date(val)
        {
            if (val == '') {
                alert('Please Select an Employee');
                return false;
            } else {
                return true;
            }
        }
        function val_resignation_date(val)
        {
            if (val == '') {
                alert('Please Select an Resignation Date');
                return false;
            } else {
                return true;
            }
        }
        function val_reason(val)
        {
            if (val == '') {
                alert('Please Write Your Reason');
                return false;
            } else {
                return true;
            }
        }

        $(document).on('click', '.decline', function () {
            var resignation_id = $(this).attr("id");
    //            alert(id);

            if (confirm('Are you sure want to decline for this leave?'))
            {
                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: {resignation_id: resignation_id, action: 'decline_resign'},
    //                   alert(are you Sure???);
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $("#" + resignation_id).remove();
    //                        alert(data);  
    //                        alert("Leave Approve successfully");  
                        window.location.href = 'resignation_data.php';
                    }
                });
            }
        });
    </script>


    <?php
} else {
    header("Location:../login.php");
}?>