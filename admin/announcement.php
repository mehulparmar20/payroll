<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Announcement';
    include'admin_header.php';
//    include '../dbconfig.php';
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
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_announcement"><i class="fa fa-plus"></i> Add Announcement</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div id='view_data'>
                
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <!-- Add Department Modal -->
    <div id="add_announcement" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Announcement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Announcement Subject <span class="text-danger">*</span></label>
                        <input id="notice_subject" name="notice_subject" class="form-control" type="text">
                    </div>
                    <div class="-group">
                        <label>Announcement Body <span class="text-danger">*</span></label>
                        <textarea id="notice" rows="5" name="notice" class="form-control" type="text"></textarea>
                    </div>
                    <div class="submit-section">
                        <button type="submit" id="submit" name="submit" onclick="addannouncement()" value="Add" class="btn btn-primary submit-btn"> Add </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /Add Department Modal  -->

    <!-- Edit Department Modal -->
    <div id="edit_announcement" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Announcement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit_submit" onsubmit="editannouncement(); return false;">
                        <div class="form-group">
                            <label>Announcement Subject<span class="text-danger">*</span></label>
                            <input class="form-control" value="" id="unotice_subject" name="unotice_subject" type="text">
                        </div>
                        <div class="form-group">
                            <label>Announcement Body<span class="text-danger">*</span></label>
                            <textarea class="form-control" value="" rows="8" cols="33" id="unotice" name="unotice" type="text"></textarea>
                            <input class="form-control" value="" id="n_id" name="n_id" type="hidden">
                        </div> 
                        <div class="submit-section">
                            <input type="submit" id="submit" name="submit" onclick="editannouncement()" value="Save" class="btn btn-primary submit-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Department Modal -->

    <script>
        $(document).ready(function () {
            view_data();
            $("#announcement").DataTable({
                ordering:false,
            });
        });
        function view_data()
        {
            var admin_id = $('#admin_id').val();
            $(".wrap-loader").show();
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {admin_id: admin_id, action: 'fetch_announcement'},
                dataType: "html",
                success: function (data)
                {
                    $('#view_data').html(data);
                    $(".wrap-loader").hide();
                }
            });
        }

        // Edit Announcement

        $(document).on('click', '.edit_data', function () {
            var n_id = $(this).attr("id");
            $(".wrap-loader").show();
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {n_id: n_id, action: 'announcement_fetch'},
                dataType: "json",
                success: function (data) {
                    $(".wrap-loader").hide();
                    $("#n_id").val(data.n_id);
                    $("#unotice_subject").val(data.notice_subject);
                    $("#unotice").val(data.notice);
                    $("#edit_announcement").modal('show');
                }
            });
        });
        // Delete Announcement

        $(document).on('click', '.delete_data', function () {
            var a_id = $(this).attr("id");
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
                            data: {n_id: a_id, action: 'announcement_delete'},
                            success: function (data)
                            {
                                swal("Announcement Delete Successfully.");
                                view_data();
                            }
                        });
                    } else {
                        swal("Your record is safe!");
                    }
                });
        });


        // Hide Announcement
        $(document).on('click', '.hide_data', function () {
            var a_id = $(this).attr("id");
            swal({
                title: "Are you sure?",
                text: "Once hide, your employess will not be able to see this announcement!",
                icon: "info",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $(".wrap-loader").show();
                        $.ajax({
                            url: 'action.php',
                            type: 'POST',
                            data: {n_id: a_id, action: 'hide_announcement'},
                            success: function (data)
                            {
                                view_data();
                            }
                        });
                    }else {
                        swal("Your record is safe!");
                    }
                });
        });


        // Show Announcement
        $(document).on('click', '.show_data', function () {
            var n_id = $(this).attr("id");
            swal({
                title: "Are you sure?",
                text: "Once visible, your employess will be able to see this announcement!",
                icon: "info",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $(".wrap-loader").show();
                        $.ajax({
                            url: 'action.php',
                            type: 'POST',
                            data: {n_id: n_id, action: 'show_announcement'},
                            success: function (data)
                            {
                                 view_data();
                            }
                        });
                    }else {
                        swal("Your record is safe!");
                    }
                });
        });

        function addannouncement() {
            var text = $("#notice").val();
            var admin_id = $("#admin_id").val();
            var subject = $("#notice_subject").val();

            if (val_subject(subject)) {
                if (val_notice(text)) {
                    $(".wrap-loader").show();
                    $.ajax({
                        url: 'insert.php',
                        type: 'POST',
                        data: {text: text, subject: subject, admin_id: admin_id, action: 'add_notice'},
                        success: function (data)
                        {
                            $('#add_announcement').modal('hide');
                            swal("Announcement Added Successfully.");
                             view_data();
                        }
                    });
                }
            }
        }

        function val_notice(val) {
            if (val == '') {
                swal({title:'Please Enter Announcement'});
                return false;
            } else {
                return true;
            }
        }
        function val_subject(val) {
            if (val == '') {
                swal({title:'Please Enter Announcement Subject'});
                return false;
            } else {
                return true;
            }
        }

        function editannouncement()
        {
            var n_id = $('#n_id').val();
            var notice = $('#unotice').val();
            var subject = $('#unotice_subject').val();
            if (val_subject(subject)) {
                if (val_notice(notice)) {
                    $(".wrap-loader").show();
                    $.ajax({
                        url: "update.php",
                        method: "POST",
                        data: {n_id: n_id, notice: notice, subject: subject, action: 'announcement_edit'},
                        dataType: "html",
                        success: function (data) {
                            $('#edit_announcement').modal('hide');
                              view_data();
                        }
                    });
                }
            }
        }
    </script>
    <?php
} else {
    header("Location:../index.php");
}?>