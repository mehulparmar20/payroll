<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'File Manager';
    include'admin_header.php';
    ?>			
    <!-- Page Content -->

    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title"><?php echo $page; ?></h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo $page; ?></li>
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
    include 'footer.php'; ?>
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
                <form method="post" id='c_file' enctype="multipart/form-data">
                    <div class="file-field">
                        Files
                        <input class="form-control" type="file" name="files[]" id="files" multiple accept=".jpg, .png, .pdf" >
                    </div>
                    <br>
                    <label class="text-danger"><b>Note :</b> You Have 20MB to Upload Your Company Document For More Space to Contact Service Providers.</label>
                    <label><b>Your remaining Storage is : </b></label>
                    <?php
                    $sql = mysqli_query($conn, " select company_upload_storage from company_admin where admin_id = '" . $_SESSION['admin_id'] . "' ");
                    while ($storage = mysqli_fetch_array($sql)) {
                        ?>
                    <label><?php echo round($storage['company_upload_storage'] / 1000) / 1000; ?> MB</label>
                        <?php
                    }
                    ?>
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
    });
        function view_data()
        {
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
                swal({title:"Please Select File To Upload"});
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
                for (var index = 0; index < totalfiles; index++) {
                    form_data.append("files[]", document.getElementById('files').files[index]);
                }
                // AJAX request
                $.ajax({
                    url: 'add_company_file.php',
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#c_file').reset();
                        $('#add_file').modal('hide');
                        view_data();
                    }
                });
            }
        });
        
        // delete file
         $(document).on('click', '.delete', function () {
            var path = $(this).attr('id');
            var admin_id = $('#admin_id').val();
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url:"update.php",
                            method:"POST",
                            data:{path:path,admin_id:admin_id,action:'company_file_delete'},
                            success: function (data)
                            {
                                swal("Deleted!", "File Delete successfully.", "success");
                                view_data();
                            }
                        });
                    } else {
                        swal("Your file is safe!");
                    }
                });
        });

        // download file
        $(document).on('click', '.download', function(){
            var path = $(this).attr('id');
            $.ajax({
                url:'file_download.php',
                method:'POST',
                data:{path:path},
                success: function (data){
                    location.relode(true);
                }
            });
        });
</script>
<?php
} else {
    header("Location:../index.php");
}


