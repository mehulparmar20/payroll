<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = 'Manage Office';
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
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addOfficeModal"><i class="fa fa-plus"></i> Add Office</a>
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
                            <th><b>Location</b></th>
                            <th><b>Created Date</b></th>
                            <th><b>Device Status</b></th>
                            <th class="text-right"><b>Action</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM office where admin_id = " . $_SESSION['admin_id'] . " ");
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td ><?php echo $row['officeName']; ?></td>
                                <td ><?php echo $row['officeLocation']; ?></td>
                                <td ><?php echo date("d/m/Y",$row['officeopenDate']); ?></td>
                                <td >
                                    <div class="menu-right">
                                        <?php if ($row['officeDevice'] == 1) { ?>
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
                                            <a class="dropdown-item editOffice" href="#" id="<?php echo $row['id']; ?>" style="color:black" title="Edit"  ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item deleteOffice" href="#" id="<?php echo $row['id']; ?>" style="color:black" title="Delete"><i class="fa fa-trash m-r-5"></i> Delete</a>
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
    <div id="addOfficeModal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="heading">Add Office</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Office Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="officeName" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Office Location <span class="text-danger">*</span></label>
                                <input class="form-control" id="officeLocation" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date </label>
                                <input class="form-control" id="officeOpenDate" type="date">
                            </div>
                        </div>
                        
                    </div>
                    <div class="submit-section" id="upload_button">
                        <input type="submit" id="addOffice" value="Add" class="btn btn-primary submit-btn">
                    </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->
    
    <?php include 'footer.php'; ?>
    <script>
    let useremail = '';
        $(document).on('click', '.editOffice', function () {
            var id = $(this).attr("id");
            $(".wrap-loader").show();
                $.ajax({
                    url: 'read.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {id: id,action: 'getOffice'},
                    success: function (data) {
                        $('#heading').html('Edit User');
                        $('#officeName').val(data.officeName);
                        $('#officeLocation').val(data.officeLocation);
                        $('#officeOpenDate').val(data.officeOpenDate);
                        $("#upload_button").html(`<input type="submit" id="editOffice" name="${data.id}" value="Edit User" class="btn btn-primary submit-btn">`);
                        $("#addOfficeModal").modal('show');
                        $(".wrap-loader").hide();
                    }
                });
            });
           $(document).on('click', '#addOffice', function () {
                var admin_id = $('#admin_id').val();
                var officeName = $('#officeName').val();
                var officeLocation = $('#officeLocation').val();
                var officeOpenDate = $('#officeOpenDate').val();
                if(checkvalid(officeName," office name")){
                    if(checkvalid(officeLocation," office location")){
                        if(checkvalid(officeOpenDate,"Select date",true)){
                            $(".wrap-loader").show();
                            $.ajax({
                                url: 'insert.php',
                                method: 'POST',
                                data: {
                                    officeName: officeName,
                                    officeLocation: officeLocation,
                                    officeOpenDate: officeOpenDate,
                                    admin_id: admin_id,
                                    action: 'addoffice'
                                },
                                success: function (data) {
                                    data = JSON.parse(data);
                                    if(data.status == 'success'){
                                        swal(data.message, "","success");
                                    }else{
                                        swal(data.message, "", 'info');
                                    }
                                    $(".wrap-loader").hide();
                                    location.reload();
                                }
                            });
                        }
                    }   
                }
            });
            $(document).on('click', '.deleteOffice', function () {
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
                               location.reload();
                           }
                        });
                    } else {
                        swal("Your record is safe!");
                    }
                });
            });
            
            $(document).on('click', "#editOffice", function(){
                var admin_id = $('#admin_id').val();
                var officeName = $('#officeName').val();
                var officeLocation = $('#officeLocation').val();
                var officeOpenDate = $('#officeOpenDate').val();
                var id = $(this).attr("name");
                if(checkvalid(officeName," office name")){
                    if(checkvalid(officeLocation," office location")){
                        if(checkvalid(officeOpenDate,"Select date",true)){
                            $(".wrap-loader").show();
                            $.ajax({
                                url: 'update.php',
                                method: 'POST',
                                data: {
                                    officeName: officeName,
                                    officeLocation: officeLocation,
                                    officeOpenDate: officeOpenDate,
                                    admin_id: admin_id,
                                    id:id,
                                    action: 'editOffice'
                                },
                                success: function (data) {
                                    data = JSON.parse(data);
                                    if(data.status == 'success'){
                                        swal(data.message, "","success");
                                    }else{
                                        swal(data.message, "", 'info');
                                    }
                                    $(".wrap-loader").hide();
                                    location.reload();
                                }
                            });
                        }
                    }   
                }
            });
            
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