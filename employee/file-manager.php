<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['employee'] == 'yes') {
    include'emp_header.php';
    
    ?>			
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">File Manager</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">File Manager</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_file"><i class="fa fa-plus"></i> Add Files</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <h3>Files</h3>
                <!--<div id="images_list" class="row row-sm">-->
                <!-- data show here -->
                <div id="view_document" class="row row-sm">
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
<div id="add_file" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="#" enctype="multipart/form-data">
                    <div class="file-field">
                        Files
                        <input class="form-control" type="file" name="files[]" id="files" multiple accept=".jpg, .png, .pdf" >
                    </div>
                    <br>
                    <label class="text-danger"><b>Note :</b>The Document Submit Only One Time After Submit Document You Cannot Change.</label>
                    <div class="submit-section">
                        <input class="btn btn-primary submit-btn" name="submit" id="submit" type="submit" value="Upload">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        view_data();
        function view_data()
        {
//            alert(10);
            $.ajax({
                url: 'view_file.php',
                success: function (data)
                {
                    $('#view_document').html(data);
                }
            });
        }

        function val_form_data(val)
        {
            if (val == '')
            {
                alert("Please Select File To Upload");
                return false;
            } else {
                return true;
            }
        }

        $('#submit').click(function () {
            var form_data = new FormData();
            // Read selected files
            var totalfiles = document.getElementById('files').files.length;

            if (val_form_data(totalfiles)) {
                if (totalfiles <= 5) {
                    for (var index = 0; index < totalfiles; index++) {
                        form_data.append("files[]", document.getElementById('files').files[index]);
                    }

                    // AJAX request
                    $.ajax({
                        url: 'add_file.php',
                        type: 'post',
                        data: form_data,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            alert(10);
//                            $('#add_file').modal('hide');
//                            view_data();
                        }
                    });
                } else {
                    alert('Please Select Only 5 Files');
                }
            }
        });
    });
</script>
<?php
}
else
{
   header("Location:../login.php");
}



