<?php
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include 'emp_header.php';
    ?>
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Change Password</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="form-group">
                        <label>Old password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" id="old_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" id="new_pass" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm password <span class=" text-danger" style="font-size:20px">*</span></label>
                        <input type="password" id="c_newpass" class="form-control">
                    </div>
                    <div class="submit-section">
                        <input type="submit" id="submit" name="submit" class="btn btn-primary submit-btn"
                               onclick="change_pass()" value="Update Password">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <?php include 'footer.php'; ?>
    <script>

        function change_pass() {
            var old_pass = document.getElementById('old_pass').value;
            var new_pass = document.getElementById('new_pass').value;
            var c_newpass = document.getElementById('c_newpass').value;
            var e_id = document.getElementById('e_id').value;
            if (val_old_pass(old_pass)) {
                if (val_new_pass(new_pass)) {
                    if (val_c_newpass(new_pass, c_newpass)) {
                        $.ajax({
                            url: 'fetch.php',
                            type: 'POST',
                            data: {e_id: e_id, old_pass: old_pass, new_pass: new_pass, action: 'change_password'},
                            success: function (data) {
                                var data = data.trim();
                                if (data == 'true') {
                                    swal({title:"Password Change Successfully"});
                                    $("#old_pass").val('');
                                    $("#new_pass").val('');
                                    $("#c_newpass").val('');
                                }else{
                                    swal({title:"Your old Password is Invalid"});
                                }


                            }
                        });
                    }
                }
            }
        }
        function val_old_pass(val) {
            if(val == ''){
                swal({title:"Please Enter Old Password"});
                return false;
            }else{
                return true;
            }
        }
        function val_new_pass(val) {
            if(val == ''){
                swal({title:"Please Enter New Password"});
                return false;
            }else{
                return true;
            }
        }
        function val_c_newpass(val1,val2) {
            if(val1 != val2){
                swal({title:"Password is Not Match"});
                return false;
            }else{
                return true;
            }
        }
    </script>
    <?php

} else {
    header("Location:../index.php");
}
?>