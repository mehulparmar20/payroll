<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = 'Manage Password';
    include'admin_header.php';
    ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title"><?php echo $page; ?></h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active"><b><?php echo $page; ?></b></li>
                    </ul>
                </div>
                <?php 
                    $sql = mysqli_query($conn, "SELECT * FROM accounting_auth where admin_id = " . $_SESSION['admin_id'] . " ");
                    $ron = mysqli_fetch_array($sql);
                    $count = mysqli_num_rows($sql);
                  if($count == 0){                  ?>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#accounting_pass"><i class="fa fa-plus"></i> Add Accounting Password</a>
                </div>
                  <?php } ?>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div>
                    <table id="department" class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 30px;"><b>No</b></th>
                                <th class="text-center"><b>Account Type</b></th>
                                <th class="text-center"><b>username</b></th>
                                <th class="text-center"><b>Last Password Change</b></th>
                                <th class="text-right"><b>Action</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                    $sql = mysqli_query($conn, "SELECT * FROM company_admin where admin_id = " . $_SESSION['admin_id'] . " ");
                                    $row = mysqli_fetch_array($sql);
                                    date_default_timezone_set('Asia/Kolkata');
                            ?>
                            <tr>
                                <td>1</td>
                                <td class="text-center">Admin</td>
                                <td class="text-center"><?php echo $row['admin_email']; ?></td>
                                <td class="text-center"><?php if($row['password_change'] == ''){ echo 'The password have not changed yet.';}else{echo date("d/m/y h:i:s A",$row['password_change']);} ?></td>
                                <td class="text-right">
                                    <div class="col-auto float-right ml-auto">
                                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#admin_pass"><i class="fa fa-pencil-square-o"></i> Change Password</a>
                                    </div>
                                </td>
                            </tr>
                            <?php if($count > 0) { date_default_timezone_set('Asia/Kolkata');?>       
                            <tr>
                                <td>2</td>
                                <td class="text-center">Accounting</td>
                                <td class="text-center"><?php echo $row['admin_email']; ?></td>
                                <td class="text-center"><?php if($ron['last_change_password'] == ''){ echo 'The password have not changed yet.';}else{echo date("d/m/y h:i:s A",$ron['last_change_password']);} ?></td>
                                <td class="text-right">
                                    <div class="col-auto float-right ml-auto">
                                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#edit_accounting_pass"><i class="fa fa-pencil-square-o"></i> Change Password</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>     
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    <!-- Add Department Modal -->
    <div id="admin_pass" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Change Admin Password</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Old password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" id="admin_old_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="check_pass()" id="admin_new_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="confirm_pass()" id="admin_c_newpass" class="form-control">
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" name="submit" class="btn btn-primary submit-btn" onclick="admin_pass()" value="Update Password" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Department Modal  -->
    

    <!-- add accounting_pass Modal -->
    <div id="accounting_pass" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chnage Accounting Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>New password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="check_pass()" id="new_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="confirm_pass()" id="c_newpass" class="form-control">
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" name="submit" class="btn btn-primary submit-btn" onclick="add_accounting()" value="Update Password" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Department Modal -->
    
    <!-- Edit accounting_pass Modal -->
    <div id="edit_accounting_pass" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chnage Accounting Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Old password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" id="account_old_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="check_pass()" id="account_new_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" onchange="confirm_pass()" id="account_c_newpass" class="form-control">
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" name="submit" class="btn btn-primary submit-btn" onclick="edit_accounting()" value="Update Password" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Department Modal -->

<script>
        function admin_pass()
        {
            var old_pass = document.getElementById('admin_old_pass').value;
            var new_pass = document.getElementById('admin_new_pass').value;
            var admin_id = document.getElementById('admin_id').value;
                $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: {admin_id:admin_id,old_pass:old_pass,new_pass:new_pass,action:'change_password'},
                    success: function(data)
                    {
                        var status = JSON.parse(data);
                        swal(status.status)
                        setInterval(function(){ window.location.href = 'change_password.php'; }, 3000)
                    }
                 });
        }
        function add_accounting(){
            var new_pass = document.getElementById('new_pass').value;
            var admin_id = document.getElementById('admin_id').value;
            $.ajax({
                        url: 'insert.php',
                        type: 'POST',
                        data: {admin_id:admin_id,new_pass:new_pass,action:'add_accounting_password'},
                        success: function(data)
                        {
                            swal(data);
                            window.location.href = 'change_password.php';
                        }
                  });
        }
        function edit_accounting(){
            var new_pass = document.getElementById('account_new_pass').value;
            var old_pass = document.getElementById('account_old_pass').value;
            var admin_id = document.getElementById('admin_id').value;
               $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: {admin_id:admin_id,old_pass:old_pass,new_pass:new_pass,action:'accounting_password'},
                    success: function(data)
                    {
                        var status = JSON.parse(data);
                        swal(status.status);
                        setInterval(function(){ window.location.href = 'change_password.php'; }, 3000)
                    }
              });
            }

</script>
<?php 

}
else 
{
    ob_end_clean();
    header("Location:../index.php");
}
?>