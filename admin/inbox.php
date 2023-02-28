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
                    <h3 class="page-title">Policies</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Policies</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_policy"><i class="fa fa-plus"></i> Add Policy</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable" >
                        <thead>
                            <tr>
                                <th>Policy Name</th>
                                <th>Policy Department</th>
                                <th>Policy Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            <?php 
                            $query = mysqli_query($conn, "select * from company_policy where policies_statuse = 1 "); //Select Data From Database and Set Limit
                            while($row = mysqli_fetch_array($query)){
                            ?>
                            <tr>
                                <td><?php echo $row['policy_name']; ?></td>
                                <td><?php echo $row['policy_department']; ?></td>
                                <td><?php echo $row['policy_description']; ?></td>
                                <td>
                                    <a href="#" id="<?php echo $row["policy_id"]; ?>" class="delete_policies" data-toggle="modal" data-target="#delete_policies" ><i class="fa fa-trash-o"  aria-hidden="true" style="color:black; font-size:15px;"></i></a> &nbsp;&nbsp;&nbsp;
                                    <a href="#" id="<?php echo $row["policy_id"]; ?>" class="edit_policies" data-toggle="modal" ><i class="fa fa-pencil" aria-hidden="true" style="color:black; font-size:15px;"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Add Policy Modal -->
        <div id="add_policy" class="modal custom-modal fade" role="dialog" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Policy Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="policy"  name="policy" >
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="4" id="description" name="description" ></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Department</label>
                            <select class="form-control"  id="department">
                                    <option>Select Department</option>
                                    <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                    <option value="<?php echo $row['departments_name']; ?>"><?php echo $row['departments_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>						
                        <div class="submit-section">
                            <input type="submit" class="btn btn-primary submit-btn" onclick="add_poloicies()" id="submit" name="submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Policy Modal -->

        <!-- Edit Policy Modal -->
        <div id="edit_policy" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Policy Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="upolicy" id="upolicy" required="">
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger" >*</span></label>
                            <textarea class="form-control" rows="4" name="udescription" id="udescription" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Department</label>
                            <input class="form-control" type="text" id="udepartment" name="udepartment" disabled="">
                        </div>
                        <input class="form-control" type="hidden" id="p_id" name="p_id">
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn" onclick="pupdate()" name="submit" id="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Policy Modal -->
        <!-- Delete Employee Modal -->
        <div class="modal custom-modal fade" id="delete_policies" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Employee</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="del_policies()" class="btn btn-primary continue-btn">Delete</a>
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
   <?php include 'footer.php'; ?>
        
        <script>
            // Delete Policies
        $(document).on('click', '.delete_policies', function () {
            var p_id = $(this).attr("id");
            $("#del_val").val(p_id);
        });
        $(document).on('click', '.edit_policies', function () {
            var p_id = $(this).attr("id");
            $.ajax({
                url: "fetch.php",
                method: "POST",
                data: {p_id: p_id, action: 'p_view'},
                datatype: "json",
                success: function (data) {
                    var user = JSON.parse(data);
                    $('#upolicy').val(user.policy_name);
                    $('#udescription').val(user.policy_description);
                    $('#udepartment').val(user.policy_department);
                    $('#p_id').val(user.policy_id);
                    $('#edit_policy').modal('show');
                }
            });
        });
            function del_policies(){
                var p_id = $("#del_val").val();
                $.ajax({
                    url: 'delete.php',
                    type: 'POST',
                    data: {p_id: p_id, action: 'delete_policies'},
                    success: function (data) {
                        swal("policies Delete successfully");
                        window.location.href = 'policies.php';
                    }
                });
            }
            // add policies
            
            function add_poloicies(){
                var policy = $('#policy').val();
                var description = $('#description').val();
                var department = $('#department').val();
                var admin_id = $('#admin_id').val();
                $.ajax({
                    url: "../control.php",
                    type: "POST",
                    data: {
                        policy: policy,
                        description: description,
                        department: department,
                        admin_id: admin_id,
                        action: 'addpolicies'},
                    datatype: "html",
                    success: function (data)
                    {
                        alert(data);
                        window.location.href = 'policies.php';
                    }
                });
            }
            

             // Edit policies
            
            function pupdate() {
            var policy_name = $('#upolicy').val();
            var policy_description = $('#udescription').val();
            var policy_department = $('#udepartment').val();
            var p_id = $('#p_id').val();
            $.ajax({
                url: "../control.php",
                type: "POST",
                data: {
                    policy_name: policy_name,
                    policy_description: policy_description,
                    policy_department: policy_department,
                    p_id: p_id,
                    action: 'pupdate'},
                    datatype: "html",
                success: function (data)
                {
                    swal(data);
                    window.location.href = 'policies.php';
                }
            });
        }
        </script>
    
    <?php
} else {
    header("Location:../login.php");
}?>