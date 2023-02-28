<?php
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include'emp_header.php';
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
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_resignation"><i class="fa fa-plus"></i> Add Resignation</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reason </th>
                                <th>Notice Date </th>
                                <th>Resignation Date </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="data_show">
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

<!-- Add Resignation Modal -->
<div id="add_resignation" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Resignation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="insert_resignation" method="post">
                    <div class="form-group">
                        <label>Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="4" id="reason" name="reason"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Notice Date <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input type="date" disabled id="notice_date" name="notice_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Resignation Date <span class="text-danger">*</span></label>
                        <div>
                            <input type="date" id="resignation_date" name="resignation_date" class="form-control">
                            <input type="hidden" name="e_id" id="e_id" value="<?php echo $_SESSION['e_id']; ?>" >
                            <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" id="insert" name="insert">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Resignation Modal -->                       

<script type="text/javascript">
    $(document).ready(function () {
        view_data();
        function view_data()
        {
            $.ajax({
                url:'view_resignation.php',
                success:function(responce){
                    $('#data_show').html(responce);
                }
            });
        }
        
        function val_reason(val)
        {
            if(val == ''){
                alert("Please Write Your Reason");
                return false;
            }else{
                return true;
            }
        }
        
        function val_notice_date(val)
        {
            if(val == ''){
                alert("Please Select Date");
                return false;
            }else{
                return true;
            }
        }
        function val_resignation_date(val)
        {
            if(val == ''){
                alert("Please Select Resignation Date");
                return false;
            }else{
                return true;
            }
        }
        
        $('#insert_resignation').on('submit', function(event) {
            event.preventDefault();
            
            var reason = document.getElementById('reason').value;
            var notice_date = document.getElementById('notice_date').value;
            var resignation_date = document.getElementById('resignation_date').value;
            var e_id = document.getElementById('e_id').value;
            var admin_id = document.getElementById('admin_id').value;
//            alert(e_id);
//            alert(admin_id);
            if(val_reason(reason)){
                if(val_notice_date(notice_date)){
                    if(val_resignation_date(resignation_date)){
                        $.ajax({
                url: '../control.php',
                method: 'post',
                data: {reason: reason,
                    notice_date: notice_date,
                    resignation_date: resignation_date,
                    e_id:e_id,
                    admin_id:admin_id,
                    action:'add_resignation'},
                dataType: 'html',
                success: function(data) {
//                    alert(data);
                    $('#insert_resignation')[0].reset();
                    $('#add_resignation').modal('hide');
                    view_data();
                   // location.reload(true);
                }
            });
                    }
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