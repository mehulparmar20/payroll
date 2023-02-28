<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Company Logo'; 
    include'admin_header.php';
    ?>
    <!-- Page Content -->
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title"><?php echo $page; ?></h3>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label"><b>Company Logo</b></label>
                    <div class="col-lg-5">
                        <input type="file" class="form-control" onchange="logo()" id="files">
                        <span class="form-text text-muted"><b>Recommended image size is 40px x 40px</b></span>
                    </div>
                    <div class="col-lg-4">
                        <div class="img-thumbnail float-right" id="view_company_logo"></div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" onclick="reload()">Save</button>
                </div>

            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <?php
    include 'footer.php';
}
else
{
    header("Location:../index.php");
}
?>
<script>
    function reload(){
        location.reload(true);
    }
    $(document).ready(function(){
        view_logo();
    });

    function view_logo()
    {
        var admin_id = $('#admin_id').val();
        $.ajax({
            url:'read.php',
            method:'POST',
            data:{admin_id:admin_id,
                action:'view_logo'},
            success:function (data){
                $('#view_company_logo').html(data);
            }
        });
    }
    function logo() {
        var form_data = new FormData();
        // Read selected files
        var totalfiles = document.getElementById('files').files.length;
        if (totalfiles <= 1) {
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }
            // AJAX request
            $.ajax({
                url: 'logo_upload.php',
                type: 'post',
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    swal(data.message);
                    view_logo();
                }
            });
        } else {
            swal('Please Select Only One File');
        }
    }
</script>