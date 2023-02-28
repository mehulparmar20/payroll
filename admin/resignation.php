<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    ?>					
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Resignation</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Resignation</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div id="view_data">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Add Resignation Modal -->
    <div id="edit_resignation" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Resignation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit_form" method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Manager Name <span class="text-danger">*</span></label>
                                    <input type="text" name="emp_id" id="rmanager_name" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Manager Designation <span class="text-danger">*</span></label>
                                    <input type="text" name="emp_id" id="rmanager_designation" value="" class="form-control">
                                </div>
                            </div> 
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Select Staff<span class="text-danger"></span></label>
                                    <select class="form-control" id="re_id" name="e_id">
                                        <option value="error">--- select employee ---</option>
                                        <?php
                                        //show employees on add salary form from employees table
                                        $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname'] ?></option>
                                        <?php } ?>
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Action<span class="text-danger"></span></label>
                                    <select class="form-control" id="r_action" name="r_action">
                                        <option value="">--- select employee ---</option>
                                        <option value="11">Approve</option>
                                        <option value="00">Decline</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Notice Date <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="date" disabled class="form-control" value="<?php echo date("Y-m-d"); ?>" id="rnotice_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Resignation Date <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="date" id="rresignation_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="4" name="reason" id="rreason"></textarea>
                                    <input id="rresignation_id" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn" name="insert" id="insert">Submit</button>
                        </div>
                    </form>
               </div>
            </div>
        </div>
    </div>
    <!-- /Add Resignation Modal -->
    <?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        resignation_show();
        $("#resignation").DataTable();

        function val_manager_name(val) {
            if (val == '') {
                swal('Please Write Manager Name');
                return false;
            } else {
                return true;
            }
        }

        function val_manager_designation(val) {
            if (val == '') {
                swal('Please Write Manager Designation');
                return false;
            } else {
                return true;
            }
        }

        function val_e_id(val) {
            if (val == 'error') {
                swal('Please Select an Employee');
                return false;
            } else {
                return true;
            }
        }

        function val_notice_date(val) {
            if (val == '') {
                swal('Please Select an Employee');
                return false;
            } else {
                return true;
            }
        }

        function val_resignation_date(val) {
            if (val == '') {
                swal('Please Select an Resignation Date');
                return false;
            } else {
                return true;
            }
        }

        function val_reason(val) {
            if (val == '') {
                swal('Please Write Your Reason');
                return false;
            } else {
                return true;
            }
        }

        // fetch edit data
        $(document).on('click', '.edit_data', function () {
            var resignation_id = $(this).attr("id");

            $.ajax({
                url: "../control.php",
                method: "POST",
                data: {resignation_id: resignation_id, action: 'fetch_resignation'},
                dataType: "json",
                success: function (data) {
                    $('#rresignation_id').val(data.resignation_id);
                    $("#re_id").val(data.e_id);
                    $("#rnotice_date").val(data.notice_date);
                    $("#rresignation_date").val(data.resignation_date);
                    $("#r_action").val(data.request_status);
                    $("#rreason").val(data.reason);
                    $("#rmanager_name").val(data.manager_name);
                    $("#rmanager_designation").val(data.manager_designation);
                    $('#edit_resignation').modal('show');
                }
            });
        });

        // update function
        $('#edit_form').on('submit', function (event) {
            event.preventDefault();
            var e_id = $('#re_id').val();
            var notice_date = $('#rnotice_date').val();
            var resignation_date = $('#rresignation_date').val();
            var reason = $('#rreason').val();
            var r_action = $('#r_action').val();
            var admin_id = $('#admin_id').val();
            var resignation_id = $('#rresignation_id').val();
            var manager_name = $('#rmanager_name').val();
            var manager_designation = $('#rmanager_designation').val();

            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_notice_date(notice_date)) {
                            if (val_resignation_date(resignation_date)) {
                                if (val_reason(reason)) {
//                            if (val_admin_id(admin_id)) {
                                    $.ajax({
                                        url: '../control.php',
                                        method: 'post',
                                        data: {
                                            reason: reason,
                                            notice_date: notice_date,
                                            resignation_date: resignation_date,
                                            e_id: e_id,
                                            manager_name: manager_name,
                                            r_action: r_action,
                                            manager_designation: manager_designation,
                                            resignation_id: resignation_id,
                                            admin_id: admin_id,
                                            action: 'edit_resignation'
                                        },
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#edit_form')[0].reset();
                                            $('#edit_resignation').modal('hide');
                                            resignation_show();
                                        }
                                    });
//                            }
                                }
                            }
                        }
                    }
                }
            }
        });

        // delete data
        $(document).on('click', '.delete', function () {
            var resignation_id = $(this).attr("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this record!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '../control.php',
                            type: 'POST',
                            data: {
                                id: resignation_id,
                                action: 'delete_resignation'
                            },
                            success: function (data) {
                                swal("Record removed successfully");
                                resignation_show();
                            }
                        });
                    } else {
                        swal("Your Recorded is safe!");
                    }
                });
        });
    });
    function resignation_show() {
        var admin_id = $("#admin_id").val();
        $.ajax({
            url: 'read.php',
            dataType: 'html',
            type: 'POST',
            data: {admin_id: admin_id, action: 'resignation_fetch'},
            success: function (data) {
                $('#view_data').html(data);
                datatable();
            }
        });
    }
    function datatable(){
            var table = $('#resignation').DataTable( {
                    ordering:false,
                } );
 
                new $.fn.dataTable.FixedColumns( table );
        }
</script>
<?php 
}
else 
{
    header("Location:../index.php?msg=Your Session is Expired");
}

?>