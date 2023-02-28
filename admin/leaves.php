<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = "Leaves";
    include'admin_header.php';
    ?>				
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
            
        </div>
    </div>
    <!-- /Page Header -->
    <!-- Leave Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-info">
                <?php
                $sql1 = mysqli_query($conn, "SELECT * FROM `leaves` WHERE leave_status = '0' and admin_id = " . $_SESSION['admin_id'] . " ");
                $countleave = mysqli_num_rows($sql1);
                ?>
                <h3 style="color: black"><b><?php echo $countleave; ?></b></h3>
                <span style="color: black"><b>Leave Request</b></span>
            </div>
        </div>
    </div>
    <!-- /Leave Statistics -->

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="leave" class="table table-striped custom-table mb-0 ">
                    <thead>
                        <tr>
                            <th width="30%"><b>Employee</b></th>
                            <th><marquee behavior="scroll" direction="left" scrollamount="1"><b>Leave Type</b></marquee></th>
                            <th><b>From</b></th>
                            <th><b>To</b></th>
                            <th width="10%"><marquee behavior="scroll" direction="left" scrollamount="1"><b>No of Days</b></marquee></th>
                            <th><b>Reason</b></th>
                            <th class="text-center"><b>Status</b></th>
                            <th class="text-right"><b>Actions</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $timesql = mysqli_query($conn, "SELECT * FROM company_time where time_id = " . $_SESSION['shift_id'] . " ");
                        $time = mysqli_fetch_assoc($sql);
                        $timezone = $time["timezone"];
                        date_default_timezone_set($timezone);
                        $sql = mysqli_query($conn, 'select * from leaves INNER JOIN employee on leaves.e_id = employee.e_id INNER JOIN add_leave on leaves.leave_id = add_leave.leave_id where leaves.admin_id = "' . $_SESSION['admin_id'] . '" ORDER BY r_leave_id DESC ');
                        while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td><?php $f_name = $row['e_firstname']; $l_name = $row['e_lastname'];  echo $f_name." ".$l_name; ?></td>
                                    <td><?php echo $row['leave_type']; ?></td>
                                    <td><?php echo date("d/m/Y",$row['from_date']);?></td>
                                    <td><?php echo date("d/m/Y", $row['to_date']); ?></td>
                                    <td><?php echo $row['number_day']; ?></td>
                                    <td><div style="width: 100%;height: 80px;margin: 0;padding: 0;overflow-y: scroll;"><?php echo $row['leave_reason']; ?></div></td>
                                    <td class="text-center">
                                            <div class="menu-right">
                                                <?php if($row['leave_status'] == 0){ ?>
                                                <i class="fa fa-dot-circle-o text-purple"></i> New
                                                <?php } elseif($row['leave_status'] == 11) { ?>
                                                <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                <?php }elseif($row['leave_status'] == 12) { ?>
                                                 <i class="fa fa-dot-circle-o text-blue"></i> Pending
                                                <?php }else{ ?>
                                                <i class="fa fa-dot-circle-o text-danger"></i> Declined
                                                <?php } ?>
                                            </div>

                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(65px, 32px, 0px);">
                                                <?php if($row['leave_status'] == 0){ ?>
                                                    <a class="dropdown-item approve" href="#" title="Approve" id="<?php echo $row['r_leave_id']; ?>" style="color:green" ><i class="fa fa-check m-r-5"></i> Approve</a>
                                                    <a class="dropdown-item decline" href="#" title="Decline" id="<?php echo $row['r_leave_id']; ?>" style="color:red" ><i class="fa fa-times m-r-5"></i> Decline</a>
                                                    <a class="dropdown-item pending" href="#" title="Pending" id="<?php echo $row['r_leave_id']; ?>" style="color:blue" ><i class="fa fa-clock-o"></i> Pending</a>
                                                <?php } elseif($row['leave_status'] == 11) { ?>
                                                    <a class="dropdown-item decline" href="#" title="Decline" id="<?php echo $row['r_leave_id']; ?>" style="color:red" ><i class="fa fa-times m-r-5"></i> Decline</a>
                                                    <a class="dropdown-item pending" href="#" title="Pending" id="<?php echo $row['r_leave_id']; ?>" style="color:blue" ><i class="fa fa-clock-o"></i> Pending</a>
                                                <?php }elseif($row['leave_status'] == 12) { ?>
                                                    <a class="dropdown-item approve" href="#" title="Approve" id="<?php echo $row['r_leave_id']; ?>" style="color:green" ><i class="fa fa-check m-r-5"></i> Approve</a>
                                                    <a class="dropdown-item decline" href="#" title="Decline" id="<?php echo $row['r_leave_id']; ?>" style="color:red" ><i class="fa fa-times m-r-5"></i> Decline</a>
                                                <?php }else{ ?>
                                                    <a class="dropdown-item approve" href="#" title="Approve" id="<?php echo $row['r_leave_id']; ?>" style="color:green" ><i class="fa fa-check m-r-5"></i> Approve</a>
                                                    <a class="dropdown-item pending" href="#" title="Pending" id="<?php echo $row['r_leave_id']; ?>" style="color:blue" ><i class="fa fa-clock-o"></i> Pending</a>
                                                <?php } ?>

                                                <a class="dropdown-item delete" href="#" title="Delete" id="<?php echo $row['r_leave_id']; ?>" style="color:black" ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
    
    <!-- /Page Content -->

    <!-- Add Leave Modal -->
    <div id="add_leave" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control">
                                <option>Select Leave Type</option>
                                <?php 
                                
                                    $q1 = mysqli_query($conn, "SELECT * FROM add_leave WHERE admin_id = '".$_SESSION['admin_id']."'");
                                    while($row = mysqli_fetch_array($q1))
                                    {
                                    ?>
                                
                                <option value="<?php echo $row['leave_type']; ?>"><?php echo $row['leave_type']; ?></option>
                                    <?php } ?>
                            </select>
                            
                        </div>
                        <div class="form-group">
                            <label>From <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>To <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Leave Reason <span class="text-danger">*</span></label>
                            <textarea rows="4" class="form-control"></textarea>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Leave Modal -->
<!-- Delete Employee Modal -->
        <div class="modal custom-modal fade" id="delete_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to Delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="delete_leave()" class="btn btn-primary continue-btn">Delete</a>
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
        <!-- Delete Employee Modal -->
        <div class="modal custom-modal fade" id="approve_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Approve Leave</h3>
                            <p>Are you sure want to Approve?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="approve_employee()" class="btn btn-primary continue-btn">Approve</a>
                                    <input type="hidden" value="" id="approve_val">
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
                <!-- Delete Employee Modal -->
        <div class="modal custom-modal fade" id="pending_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Pending Leave</h3>
                            <p>Are you sure want to Pending?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="pending_employee()" class="btn btn-primary continue-btn">Pending</a>
                                    <input type="hidden" value="" id="pending_val">
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
        <!-- Decline Employee Modal -->
        <div class="modal custom-modal fade" id="decline_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Decline Leave</h3>
                            <p>Are you sure want to Decline?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="decline_leave()" class="btn btn-primary continue-btn">Decline</a>
                                    <input type="hidden" value="" id="decline_val">
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
    
    <?php  include 'footer.php'; ?>
    <script>
         $(document).ready(function (){

                $('#leave').DataTable({
                    scrollY: 'auto',
                    scrollX: 'auto',
                    ordering: false,
            });
        });
         // function for delete data
        $(document).on('click', '.delete', function () {
            var leave_id = $(this).attr("id");
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
                            type: 'POST',
                            data: {l_id: leave_id,action:'leave_delete'},
                            success: function(data) {
                                $("#delete_leave").modal("hide");
                                swal("Leave delete successfully.", "", "success");
                                setTimeout(function(){
                                     	window.location.href = 'leaves.php';
                                    }, 2000);//wait 2 seconds
                                
                            }
                        });
                    } else {
                        swal("Your Recorded is safe!");
                    }
                });
        });

        
        $(document).on('click', '.approve', function(){  
          var leave_id = $(this).attr("id");
            $("#approve_val").val(leave_id);
            $("#approve_leave").modal("show");
        });
        function approve_employee(){
            $(".wrap-loader").show();
            var id = $("#approve_val").val();
                $.ajax({
                   url: 'action.php',
                   type: 'POST',
                   data: {l_id: id,action:'approve'},
                   success: function(data) {
                       $("#approve_leave").modal("hide");
                        swal("Leave approved successfully.", "", "success");
                        setTimeout(function(){
                             	window.location.href = 'leaves.php';
                            }, 2000);//wait 2 seconds
                   }
                });
            }
        
        
         $(document).on('click', '.decline', function(){  
          var id = $(this).attr("id");  
          $("#decline_val").val(id);  
          $("#decline_leave").modal("show");
            });
            function decline_leave()
            {
                $(".wrap-loader").show();
                var id = $("#decline_val").val();
                $.ajax({
                   url: 'action.php',
                   type: 'POST',
                   data: {l_id: id,action:'decline'},
                   success: function(data) {
                       $("#delcine_leave").modal("hide");
                       swal("Leave decline successfully.", "", "success");
                        setTimeout(function(){
                             	window.location.href = 'leaves.php';
                            }, 2000);//wait 2 seconds
                   }
                });
            }
            
            $(document).on('click', '.pending', function(){  
                  var id = $(this).attr("id");
                  $("#pending_val").val(id);
                  $("#pending_leave").modal("show");
            });
                function pending_employee()
                {
                    $(".wrap-loader").show();
                    var id = $("#pending_val").val();
                    $.ajax({
                       url: 'action.php',
                       type: 'POST',
                       data: {l_id: id,action:'pending'},
                       success: function(data) {
                            $("#pending_leave").modal("hide");
                            swal("Leave pending successfully.", "", "success");
                            setTimeout(function(){
                                 	window.location.href = 'leaves.php';
                                }, 2000);//wait 2 seconds
                       }
                    });
                }
      
    </script>
    
    
    <?php
    }
 else {
        header("Location:../index.php");
    }?>