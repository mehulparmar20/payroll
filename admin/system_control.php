<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    include '../dbconfig.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Break Off List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Break Off List</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus-circle"></i> Break Off List</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div id="table_view">

            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="add_user" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Break Off List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add_new_user">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>User's Name <span class="text-danger">*</span></label>
                                    <select onchange="show()" id="user_name" name="user_name" class="custom-select"> 
                                        <option value="">Select Employee</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM employee where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['e_id'].",".$row['e_firstname']." ".$row['e_lastname']; ?>" ><?php echo $row['e_firstname'] . " " . $row['e_lastname']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Date </label>
                                    <input class="form-control" required id="user_date" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <input type="submit" id="submit" value="Add In Break Off List" class="btn btn-primary submit-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->

    <?php include 'footer.php'; ?>
    <script>
      $(document).ready(function () {
          table_data();
      });
            $('#add_new_user').on('submit', function (event) {
                event.preventDefault();
                var admin_id = $('#admin_id').val();
                var user_name = $('#user_name').val();
                var user_date = $('#user_date').val();
                if(val_name(user_name)) {
                    if (val_date(user_date)) {
                        $.ajax({
                            url: 'insert.php',
                            method: 'POST',
                            data: {
                                admin_id: admin_id,
                                user_name: user_name,
                                user_date: user_date,
                                action: 'break_off'
                            },
                            success: function (data) {
                                var response = JSON.parse(data);
                                $('#add_new_user')[0].reset();
                                $('#add_user').modal('hide');
                                swal(response.message);
                                table_data();
                            }
                        });
                    }
                }
            });
            
            $(document).on('click', '.delete_user', function () {
                var user_id = $(this).attr("id");
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
                                url: 'delete.php',
                                method: 'POST',
                                data: {
                                    user_id: user_id,
                                    action: 'delete_user'
                                },
                                success: function (data) {
                                    swal("User Delete Successfully!", "success");
                                    table_data();
                                }
                            });
                        }else {
                            swal("Your record is safe!");
                        }
                    });
            });
            function datatable(){
                var table = $('#users').DataTable( {
                    
                });

            new $.fn.dataTable.FixedColumns( table );
            }

            function table_data(){
                var admin_id =  $("#admin_id").val();
                $.ajax({
                    url: 'read.php',
                    method: 'POST',
                    data: {
                        admin_id: admin_id,
                        action: 'employee_of_month'
                    },
                    success: function (data) {
                        $("#table_view").html(data);
                        datatable();
                    }
                });

            }
            function val_date(val) {
                if(val == ''){
                    swal('Please select the Date');
                    return false;
                }else{
                    return true;
                }

            }

            function val_name(val) {
                if(val == ''){
                    swal('Please select the User');
                    return false;
                }else{
                    return true;
                }

            }
        
    </script>
    <?php
} else {
    header("Location:../login.php");
}
?>