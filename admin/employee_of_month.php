<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = "Employee Of Month";
    include'admin_header.php';
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
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add Employee Of Month</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div>
                <table id="users" class="table table-striped custom-table mb-0">
                    <thead>
                        <tr>
                            <th><b>No</b></th>
                            <th><b>Name</b></th>
                            <th><b>Department</b></th>
                            <th><b>Month Year</b></th>
                            <th><b>Attendance</b></th>
                            <th><b>Profit</b></th>
                            <th class="text-right"><b>Action</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM employee_performance INNER JOIN departments ON departments.departments_id = employee_performance.department where employee_performance.admin_id = " . $_SESSION['admin_id'] . " ");
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td ><?php echo $row['emp_name']; ?></td>
                                <td ><?php echo $row['departments_name']; ?></td>
                                <td ><?php echo date("F Y", $row['month_name']); ?></td>
                                <td ><?php echo $row['attendance']; ?></td>
                                <td ><?php echo $row['profit']; ?></td>
                                <td class="text-right">
                                    <a class="delete_user" href="#" id="<?php echo $row['ep_id']; ?>" style="color:black" title="Delete" data-toggle="modal" data-target="#delete_user" ><i class="fa fa-trash m-r-5"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="add_user" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee Of Month</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add_new_user">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>User's Name <span class="text-danger">*</span></label>
                                    <select id="user_name" onchange="get_details()" name="user_name" class="custom-select"> 
                                        <option value="">Select Employee</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM employee where admin_id = " . $_SESSION['admin_id'] . " and employee_status = 1 and delete_status = 0 ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['e_id'].",".$row['e_firstname']." ".$row['e_lastname'].",".$row['department'].",".$row['employee_profile']; ?>" ><?php echo $row['e_firstname'] . " " . $row['e_lastname']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Department </label>
                                    <input class="form-control" disabled id="department" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Attendance </label>
                                    <input class="form-control" disabled id="attendance" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Break </label>
                                    <input class="form-control" disabled id="break" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Profit </label>
                                    <input class="form-control" id="profit" type="text">
                                    <input class="form-control" disabled id="leave" type="hidden">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Date </label>
                                    <input class="form-control" id="month" type="date">
                                    <input class="form-control" id="profile" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <input type="submit" id="submit" value="Add Employee Of Month" class="btn btn-primary submit-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->

    <!-- Delete Announcement Modal -->
    <div class="modal custom-modal fade" id="delete_user" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete User</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="del_user()" class="btn btn-primary continue-btn">Delete</a>
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
    <!-- /Delete Announcement Modal -->
    
    <?php include 'footer.php'; ?>
    <script>
      $(document).ready(function () {
          
          datatable();
      });
            $('#add_new_user').on('submit', function (event) {
                event.preventDefault();
                var admin_id = $('#admin_id').val();
                var emp_name = $('#user_name').val();
                var department = $('#department').val();
                var attendance = $('#attendance').val();
                var breaks = $('#break').val();
                var leave   = $('#leave').val();
                var profile = $('#profile').val();
                var month = $('#month').val();
                
                $.ajax({
                    url: 'insert.php',
                    method: 'POST',
                    data: {
                        admin_id: admin_id,
                        emp_name: emp_name,
                        department: department,
                        attendance: attendance,
                        break: breaks,
                        leave: leave,
                        profile: profile,
                        month: month,
                        action: 'employee_month'
                    },
                    success: function (data) {
                        $('#add_new_user')[0].reset();
                        swal("User Added Successfully!", "success");
                        window.location.href = 'employee_of_month.php';
                    }
                });
            });
            
            $(document).on('click', '.delete_user', function () {
                var user_id = $(this).attr("id");
                $("#del_val").val(user_id);
                $("#delete_user").modal('show');
            });
            function del_user(){
                var ep_id = $("#del_val").val();
                 $.ajax({
                    url: 'delete.php',
                    method: 'POST',
                    data: {
                        ep_id: ep_id,
                        action: 'employee_month'
                    },
                    success: function (data) {
                        swal("User Delete Successfully!", "success");
                        window.location.href = 'employee_of_month.php';
                    }
                });
            }
            
             function datatable(){
            var table = $('#users').DataTable( {

                } );
 
                new $.fn.dataTable.FixedColumns( table );
        }
        
        function get_details(){
            var emp_name = $("#user_name").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                    url: 'read.php',
                    method: 'POST',
                    data: {
                        emp_name: emp_name,
                        admin_id:admin_id,
                        action: 'employee_fetch'
                    },
                    success: function (data) {
                        var js = JSON.parse(data);
                        $('#attendance').val(js.attendance);
                        $('#break').val(js.break);
                        $('#leave').val(js.leave);
                        var emp_name = $("#user_name").val();
                        var res = emp_name.split(",");
                        $('#department').val(res[2]);
                        $('#profile').val(res[3]);
                    }
                });
        }
        
    </script>
    <?php
} else {
    header("Location:../login.php");
}
?>