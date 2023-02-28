<?php
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include 'emp_header.php';
    ?>
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leaves</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leaves</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" onclick="remaining_leave()" data-target="#add_emp_leave"><i class="fa fa-plus"></i> Add Leave</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="">
                        <table id="leave" class="table table-striped custom-table mb-0">
                            <thead>
                                <tr>
                                    <th><b>#</b></th>
                                    <th><b>Leave Type</b></th>
                                    <th><b>From</b></th>
                                    <th><b>To</b></th>
                                    <th><b>Reason</b></th>
                                    <th><b>Status</b></th>
                                    <th><b>Actions</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php    $sql3 = mysqli_query($conn," SELECT * FROM `leaves` INNER JOIN add_leave on add_leave.leave_id = leaves.leave_id WHERE leaves.e_id = '".$_SESSION['e_id']."' AND leaves.admin_id = '".$_SESSION['admin_id']."' ORDER BY r_leave_id DESC ");
                        $no = 1;    
            while ($row = mysqli_fetch_array($sql3)) {
//                echo $row["leave_status"];exit();
                if ($row["leave_status"] == 12) {
                    $status = '<span href="#" style="background-color: blue;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Pending</span>';
                } else if($row["leave_status"] == 11) {
                    $status = '<span href="#" style="background-color: green;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Approved</span>';
                } elseif($row['leave_status'] == 10) {
                    $status = '<span style="background-color: red;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Decline</span>';
                }else{
                    $status = '<span style="background-color: #1c3e8a;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> New</span>';
                }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                                <td><?php echo $row["leave_type"]; ?></td>
                                <td><?php echo date("d/m/Y", $row["from_date"]); ?></td>
                                <td><?php echo date("d/m/Y", $row["to_date"]); ?></td>
                                <td><?php echo $row["leave_reason"]; ?></td>
                                <td><?php echo$status; ?></td>
                                <td><center><a class="delete" href="#" id="<?php echo $row['r_leave_id']; ?>" style="color:black" title="Delete" ><i class="fa fa-trash-o m-r-5"></i></a></center></td>
                            </tr>
            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            
        </div>
    </div>
    <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    <?php
    include 'footer.php';

?>
<div id="add_emp_leave" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Leave Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="leave_id" id="leave_id">
                            <option value="">Select Leave Type</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM add_leave where admin_id = '" . $_SESSION['admin_id'] . "' ");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option value="<?php echo $row['leave_id'] ?>"><?php echo $row['leave_type']; ?></option>
                                <?php
                            }
                            ?>	
                        </select>
                    </div>
                    <div class="form-group">
                        <label>From <span class="text-danger">*</span></label>
                        <input type="date" id="from_date" name="from_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>To <span class="text-danger">*</span></label>
                        <input type="date" id="to_date" onchange="count_leave()" name="to_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Total Days <span class="text-danger">*</span></label>
                        <input type="text" id="total_days" name="total_days" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                        <input class="form-control" readonly type="text" name="remaining_leave" id="remaining_leave">
                        <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                        <input type="hidden" name="e_id" id="e_id" value="<?php echo $_SESSION['e_id']; ?>" >
                    </div>
                    <div class="form-group">
                        <label>Leave Reason <span class="text-danger">*</span></label>
                        <textarea rows="4" class="form-control" name="leave_reason" id="leave_reason"></textarea>
                    </div>
                    <div class="submit-section">
                        <!--<button class="btn btn-primary submit-btn" name="insert" value="insert" id="insert" type="submit">Submit</button>-->
                        <input type="submit" name="insert" value="Add" onclick="add_leave()" id="insert" class="btn btn-primary submit-btn" >
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="del_leave()" class="btn btn-primary continue-btn">Delete</a>
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
        <!-- /Delete Leave Modal -->

<script type="text/javascript">

     $(document).ready(function () {

        // function for view data
        view_data();
        datatable();
        
        function view_data()
        {
            $.ajax({
                url: 'view_leave.php',
                success: function (data)
                {
                    $('#view_document').html(data);
                }
            });
        }
    });
    
     // function for delete data
        $(document).on('click', '.delete', function () {
            var leave_id = $(this).attr("id");
            $("#del_val").val(leave_id);
            $("#delete_leave").modal("show");
        });
            function del_leave()
            {
                var leave_id =  $("#del_val").val();
                $.ajax({
                    url: 'delete.php',
                    type: 'POST',
                    data: {l_id: leave_id, action: 'leave_delete'},
                    success: function (data) {
                        swal("Record removed successfully");
                        window.location.href = 'leaves-employee.php';
                    }
                });
            }
    
     // request employee function here
        function add_leave(){
            
            var leave_id = document.getElementById('leave_id').value;
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            var total_days = document.getElementById('total_days').value;
            var leave_reason = document.getElementById('leave_reason').value;
            var admin_id = document.getElementById('admin_id').value;
            var e_id = document.getElementById('e_id').value;
            if (val_leave_id(leave_id)) {
                if (val_from_date(from_date)) {
                    if (val_to_date(to_date)) {
                        if (val_leave_reason(leave_reason)) {
                            $(".wrap-loader").show();
                                $.ajax({
                                    url: "../control.php",
                                    method: "POST",
                                    data: {leave_id: leave_id,
                                        from_date: from_date,
                                        to_date: to_date,
                                        leave_reason: leave_reason,
                                        total_days: total_days,
                                        admin_id: admin_id,
                                        e_id: e_id,
                                        action: "request_leave"},
                                    datatype: "html",
                                    success: function (data)
                                    {
                                        $(".wrap-loader").hide();
                                        swal("Leave Apply Successfully.", "", "success");
                                        $('#add_emp_leave').modal('hide');
                                        window.location.href='leaves-employee.php';
                                    }
                                });
                            }
                        }
                    }
                }
            }
            
             function val_leave_id(val)
        {
            if(val == ''){
                swal("Please Select Leave Type");
                return false;
            } else {
                return true;
            }
        }
        function val_from_date(val)
        {
            if(val == ''){
                swal({
                      text: "Please Select From Date!",
                      icon: "warning",
                      button: "ok!",
                    });
                return false;
            } else {
                return true;
            }
        }
        function val_to_date(val)
        {
            if(val == ''){
                swal({
                      text: "Please Select To Date!",
                      icon: "warning",
                      button: "ok!",
                    });
                return false;
            } else {
                return true;
            }
        }
        function val_leave_reason(val)
        {
            if(val == ''){
                swal({
                      text: "Please Write Your Leave Reason!",
                      icon: "warning",
                      button: "ok!",
                    });
                return false;
            } else {
                return true;
            }
        }
        
    function count_leave()
        {
          var from_date = document.getElementById('from_date').value;
          var to_date = document.getElementById('to_date').value;  
           var date = new Date(from_date);
           var date1 = new Date(to_date);
          const ONE_DAY = 1000 * 60 * 60 * 24;
          var days = Math.abs(date - date1); 
          const diff = Math.round(days / ONE_DAY);
          document.getElementById('total_days').value = diff + 1;
        }
        
         function datatable(){
            var table = $('#leave').DataTable();
             
         }
         
         function remaining_leave(){
             var e_id = document.getElementById('e_id').value;
                $.ajax({
                url: "fetch.php",
                method: "POST",
                data: {e_id: e_id,action: "show_remaining_leave"},
                datatype: "html",
                success: function (data)
                {
                    $('#remaining_leave').val(data);
                }
            });
         }
         
         
</script>
<?php
}
else
{
   header("Location:../login.php");
}