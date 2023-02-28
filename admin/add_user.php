<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = 'Manage User';
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
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add User</a>
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
                            <th><b>Email</b></th>
                            <th><b>Created Date</b></th>
                            <th><b>Role</b></th>
                            <th><b>Status</b></th>
                            <th class="text-right"><b>Action</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM add_users where admin_id = " . $_SESSION['admin_id'] . " ");
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td ><?php echo $row['user_name']; ?></td>
                                <td ><?php echo $row['user_email']; ?></td>
                                <td ><?php echo date("d/m/Y",$row['user_add_date']); ?></td>
                                <td ><?php echo $row['user_type']; ?></td>
                                <td >
                                    <div class="menu-right">
                                        <?php if ($row['user_status'] == 1) { ?>
                                            <i class="fa fa-dot-circle-o text-danger"></i> Deactivate
                                        <?php } else { ?>
                                            <i class="fa fa-dot-circle-o text-success"></i> Active
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(65px, 32px, 0px);">
                                            <a class="dropdown-item active_user" href="#" id="<?php echo $row['user_id']; ?>" style="color:green" title="Activate" data-toggle="modal" data-target="#activate" ><i class="fa fa-check-circle"></i> Active</a>
                                            <a class="dropdown-item deactive_user" href="#" id="<?php echo $row['user_id']; ?>" style="color:red" title="Deactivate" data-toggle="modal" data-target="#deactivate" ><i class="fa fa-times-circle"></i> Deactivate</a>
                                            <a class="dropdown-item edit_user" href="#" id="<?php echo $row['user_id']; ?>" style="color:black" title="Edit"  ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item delete_user" href="#" id="<?php echo $row['user_id']; ?>" style="color:black" title="Delete" data-toggle="modal" data-target="#delete_user" ><i class="fa fa-trash m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
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
                    <h5 class="modal-title" id="heading">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>User's Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="user_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input class="form-control" id="user_email" type="email">
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Password <span class='text-blue' id="pass-note"></span></label>
                                <input class="form-control" id="user_password" type="password">
                            </div>
                        </div>
                        <div id="show_password">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input class="form-control" id="c_password" type="password">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Role </label>
                                <input class="form-control" id="user_type" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date </label>
                                <input class="form-control" id="user_date" type="date">
                            </div>
                        </div>

                    </div>
                    <div class="table-responsive m-t-15">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Modules</th>
                                    <th class="text-center">Employee</th>
                                    <th class="text-center">Payroll</th>
                                    <th class="text-center">Attendance</th>
                                    <th class="text-center">Break</th>
                                    <th class="text-center">Leave</th>
                                    <th class="text-center">Letters</th>
                                    <th class="text-center">Administration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Allow Access</td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="employee" class="check">
                                            <label for="employee" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="payroll" class="check">
                                            <label for="payroll" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="attendance" class="check">
                                            <label for="attendance" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="break" class="check">
                                            <label for="break" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="leave" class="check">
                                            <label for="leave" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="letters" class="check">
                                            <label for="letters" class="checktoggle">checkbox</label>
                                            <input class="form-control" id="user_id" type="hidden">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-toggle">
                                            <input type="checkbox" id="administration" class="check">
                                            <label for="administration" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="submit-section" id="upload_button">
                        <input type="submit" id="submit" value="Add User" onclick="add_user()" class="btn btn-primary submit-btn">
                    </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->
    <!-- edit User Modal -->
    <div id="edit_user" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" id="heading">
                    <div id="heading">
                        <h5 class="modal-title">Edit User</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>User's Name <span class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Username" id="euser_name" type="text">
                                    <input class="form-control" id="euser_id" type="hidden">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Email" id="euser_email" type="email">

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Role </label>
                                    <input class="form-control" placeholder="User role" id="euser_type" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Date </label>
                                    <input class="form-control" id="euser_date" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive m-t-15">
                            <table class="table table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Modules</th>
                                        <th class="text-center">Employee</th>
                                        <th class="text-center">Payroll</th>
                                        <th class="text-center">Attendance</th>
                                        <th class="text-center">Break</th>
                                        <th class="text-center">Leave</th>
                                        <th class="text-center">Letters</th>
                                        <th class="text-center">Administration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Allow Access</td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="eemployee" class="check">
                                                <label for="eemployee" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="epayroll" class="check">
                                                <label for="epayroll" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="eattendance" class="check">
                                                <label for="eattendance" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="ebreak" class="check">
                                                <label for="ebreak" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="eleave" class="check">
                                                <label for="eleave" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="eletters" class="check">
                                                <label for="eletters" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-toggle">
                                                <input type="checkbox" id="eadministration" class="check">
                                                <label for="eadministration" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <div class="submit-section" id="update_button">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /edit User Modal -->
    
    <?php include 'footer.php'; ?>
    <script>
    let useremail = '';
        $(document).on('click', '.edit_user', function () {
            var user_id = $(this).attr("id");
            $(".wrap-loader").show();
                $.ajax({
                    url: 'read.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {user_id: user_id,action: 'view_user'},
                    success: function (data) {
                        
                        $('#heading').html('Edit User');
                        $('#user_id').val(data.user_id);
                        $('#user_name').val(data.user_name);
                        useremail = data.user_email;
                        $('#user_email').val(data.user_email);
                        $('#user_type').val(data.user_type);
                        $('#user_date').val(data.user_add_date);
                        if (data.employee == 1)
                        {
                            document.getElementById("employee").checked = true;
                        }else{
                            document.getElementById("employee").checked = false;
                        }
                        if (data.payroll == 1)
                        {
                            document.getElementById("payroll").checked = true;
                        }else{
                            document.getElementById("payroll").checked = false;
                        }
                        if (data.attendance == 1)
                        {
                            document.getElementById("attendance").checked = true;
                        }else{
                            document.getElementById("attendance").checked = false;
                        }
                        if (data.break == 1)
                        {
                            document.getElementById("break").checked = true;
                        }else{
                            document.getElementById("break").checked = false;
                        }
                        if (data.leave == 1)
                        {
                            document.getElementById("leave").checked = true;
                        }else{
                            document.getElementById("leave").checked = false;
                        }
                        if (data.letters == 1)
                        {
                            document.getElementById("letters").checked = true;
                        }else{
                            document.getElementById("letters").checked = false;
                        }
                        if (data.administration == 1)
                        {
                            document.getElementById("administration").checked = true;
                        }else{
                            document.getElementById("administration").checked = false;
                        }
                        $("#show_password").hide();
                        $("#pass-note").html("Note: Do not enter password if you want to keep it same old password.");
                        $("#upload_button").html('<input type="submit" id="submit" value="Edit User" onclick="edit_user()" class="btn btn-primary submit-btn">');
                        $("#add_user").modal('show');
                        $(".wrap-loader").hide();
                    }
                });
            });
           async function add_user() {
                var admin_id = $('#admin_id').val();
                var user_name = $('#user_name').val();
                var user_email = $('#user_email').val();
                var user_password = $('#user_password').val();
                var confirm = $("#c_password").val();
                var user_type = $('#user_type').val();
                var user_date = $('#user_date').val();
                if ($('#employee').is(':checked')) {
                    var employee = 1;
                } else {
                    var employee = 0;
                }
                if ($('#payroll').is(':checked')) {
                    var payroll = 1;
                } else {
                    var payroll = 0;
                }
                if ($('#attendance').is(':checked')) {
                    var attendance = 1;
                } else {
                    var attendance = 0;
                }
                if ($('#break').is(':checked')) {
                    var breaks = 1;
                } else {
                    var breaks = 0;
                }
                if ($('#leave').is(':checked')) {
                    var leave = 1;
                } else {
                    var leave = 0;
                }
                if ($('#letters').is(':checked')) {
                    var letters = 1;
                } else {
                    var letters = 0;
                }
                if ($('#administration').is(':checked')) {
                    var administration = 1;
                } else {
                    var administration = 0;
                }
                
                if(checkvalid(user_name," username")){
                    if(checkvalid(user_email,"valid email")){
                        let resdata = await emailexist(user_email);
                        const status = JSON.parse(resdata).status;
                        console.log(resdata,status);
                        if(status === 'new'){
                            if(checkvalid(user_password," strong password")){
                                if(confirmpassword(user_password,confirm)){
                                    if(checkvalid(user_type," user type")){
                                        if(checkvalid(user_date,"Select date",true)){
                                            if(administration == 1 || letters == 1 || leave == 1|| breaks == 1|| attendance == 1|| payroll == 1 || employee ==1){
                                                $(".wrap-loader").show();
                                                $.ajax({
                                                    url: 'insert.php',
                                                    method: 'POST',
                                                    data: {
                                                        employee: employee,
                                                        payroll: payroll,
                                                        attendance: attendance,
                                                        breaks: breaks,
                                                        leave: leave,
                                                        letters: letters,
                                                        administration: administration,
                                                        admin_id: admin_id,
                                                        user_name: user_name,
                                                        user_email: user_email,
                                                        user_password: user_password,
                                                        user_type: user_type,
                                                        user_date: user_date,
                                                        action: 'add_user'
                                                    },
                                                    success: function (data) {
                                                        swal("User Added Successfully!", "","success");
                                                        $(".wrap-loader").hide();
                                                        window.location.href = 'add_user.php';
                                                    }
                                                });
                                            }else{
                                                swal("Atleast one module must be checked!", "","info");
                                            }
                                        }   
                                    }
                                }
                            }
                        }   
                    }   
                }
            }
             $(document).on('click', '.active_user', function () {
                 var user_id = $(this).attr("id");
                 swal({
                     title: "Are you sure?",
                     text: "Once Activate, user will able to login this account!",
                     icon: "warning",
                     buttons: true,
                     dangerMode: false,
                 })
                     .then((willDelete) => {
                         if (willDelete) {
                             $(".wrap-loader").show();
                             $.ajax({
                                 url: 'action.php',
                                 type: 'post',
                                 data: {user_id: user_id, action: 'activate_user'},
                                 success: function (data) {
                                     $(".wrap-loader").hide();
                                     window.location.href = 'add_user.php';
                                 }
                             });
                         } else {
                             swal("Your record is safe!");
                         }
                     });
            });
             $(document).on('click', '.deactive_user', function () {
                 var user_id = $(this).attr("id");
                 swal({
                     title: "Are you sure?",
                     text: "Once deactivate, you will not be able to login this account!",
                     icon: "warning",
                     buttons: true,
                     dangerMode: false,
                 })
                     .then((willDelete) => {
                         if (willDelete) {
                             $(".wrap-loader").show();
                             $.ajax({
                                 url: 'action.php',
                                 type: 'post',
                                 data: {user_id: user_id, action: 'deactivate_user'},
                                 success: function (data) {
                                     $(".wrap-loader").hide();
                                     window.location.href = 'add_user.php';
                                 }
                             });
                         } else {
                             swal("Your record is safe!");
                         }
                     });
            });
             $(document).on('click', '.delete_user', function () {
                 var user_id = $(this).attr("id");
                 swal({
                     title: "Are you sure?",
                     text: "Once deleted, you will not be able to recover this record!",
                     icon: "warning",
                     buttons: true,
                     dangerMode: true,
                 })
                     .then((willDelete) => {
                         if (willDelete) {
                             $(".wrap-loader").show();
                             $.ajax({
                                 url: 'delete.php',
                                 type: 'post',
                                 data: {user_id: user_id, action: 'user_delete'},
                                 success: function (data) {
                                     $(".wrap-loader").hide();
                                     window.location.href = 'add_user.php';
                                 }
                             });
                         } else {
                             swal("Your record is safe!");
                         }
                     });
            });
            
            async function edit_user(){
                var admin_id = $('#admin_id').val();
                var user_name = $('#user_name').val();
                var user_email = $('#user_email').val();
                var user_password = $('#user_password').val();
                var user_type = $('#user_type').val();
                var user_date = $('#user_date').val();
                var user_id = $('#user_id').val();
                
                if ($('#employee').is(':checked')) {
                    var employee = 1;
                } else {
                    var employee = 0;
                }
                if ($('#payroll').is(':checked')) {
                    var payroll = 1;
                } else {
                    var payroll = 0;
                }
                if ($('#attendance').is(':checked')) {
                    var attendance = 1;
                } else {
                    var attendance = 0;
                }
                if ($('#break').is(':checked')) {
                    var breaks = 1;
                } else {
                    var breaks = 0;
                }
                if ($('#leave').is(':checked')) {
                    var leave = 1;
                } else {
                    var leave = 0;
                }
                if ($('#letters').is(':checked')) {
                    var letters = 1;
                } else {
                    var letters = 0;
                }
                if ($('#administration').is(':checked')) {
                    var administration = 1;
                } else {
                    var administration = 0;
                }
                if(checkvalid(user_name," username")){
                    if(checkvalid(user_email,"valid email")){
                        let resdata = await emailexist(user_email,useremail,true);
                        const status = JSON.parse(resdata).status;
                        console.log(resdata,status);
                        if(status === 'new'){
                            if(checkvalid(user_type," user type")){
                                if(checkvalid(user_date,"Select date",true)){
                                    if(administration == 1 || letters == 1 || leave == 1|| breaks == 1|| attendance == 1|| payroll == 1 || employee ==1){
                                        $(".wrap-loader").show();
                                        $.ajax({
                                            url: 'update.php',
                                            method: 'POST',
                                            data: {
                                                employee: employee,
                                                payroll: payroll,
                                                attendance: attendance,
                                                breaks: breaks,
                                                leave: leave,
                                                letters: letters,
                                                administration: administration,
                                                admin_id: admin_id,
                                                user_id: user_id,
                                                user_name: user_name,
                                                user_email: user_email,
                                                user_password: user_password,
                                                user_type: user_type,
                                                user_date: user_date,
                                                action: 'edit_user'
                                            },
                                            success: function (data) {
                                                swal("User Edit Successfully!","", "success");
                                                $(".wrap-loader").hide();
                                                window.location.href = 'add_user.php';
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $(document).ready(function () {
               $("#users").dataTable({
                   ordering:false
               });
            });
        
    </script>
    <?php
} else {
    header("Location:../index.php");
}
?>