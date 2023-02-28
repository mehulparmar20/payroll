<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    include '../dbconfig.php';
    ?>
					<div class="row">
						<div class="col-md-6 offset-md-3">
						
							<!-- Page Header -->
							<div class="page-header">
								<div class="row">
									<div class="col-sm-12">
                                       <h3 class="page-title"><b>Change Password</b></h3>
									</div>
								</div>
							</div>
							<!-- /Page Header -->
							
                            <form id="change_pass">
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
                                    <input type="submit" id="submit" name="submit" class="btn btn-primary submit-btn" onclick="change_pass()" value="Update Password" >
								</div>
							</form>
						</div>
					</div>
<?php    include 'footer.php';   ?>
<script>

        function change_pass()
        {
            var old_pass = document.getElementById('old_pass').value;
            var new_pass = document.getElementById('new_pass').value;
            var c_newpass = document.getElementById('c_newpass').value;
            var admin_id = document.getElementById('admin_id').value;
            alert(old_pass);
            if(old_pass != '' )
            {    if(new_pass == c_newpass )
                {    
                    $.ajax({
                        url: '../control.php',
                        type: 'POST',
                        data: {admin_id:admin_id,old_pass:old_pass,new_pass:new_pass,action:'change_password'},
                        success: function(data) 
                        {
                            alert(data);
                            window.location.href = 'change-password.php';
                        }
                     });
                }
                else
                {
                    alert("Password Not Match");
                }
            }
            else
            {
                alert("Please Enter Old Password");
            }
        }
</script>
<?php 

}
else 
{
    header("Location:../index.php");
}
?>