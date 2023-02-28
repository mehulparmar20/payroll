<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Holidays';
    include'admin_header.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title"><?php echo $page;?> </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_holiday"><i class="fa fa-plus"></i> Add Holiday</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" >
                <table id="holiday" class="table table-striped custom-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title </th>
                            <th>Holiday Date</th>
                            <th>Holiday Description</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "select * from holidays where admin_id = '" . $_SESSION['admin_id'] . "' ORDER BY holiday_id DESC");
                        $no = 1;
                        $id = '';
                        while ($row = mysqli_fetch_assoc($sql)) {
                            ?>

                            <tr class="holiday-upcoming">
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['holiday_name']; ?></td>
                                <td><?php echo date("d/m/Y", $row['holiday_date']); ?></td>
                                <td><?php echo $row['holiday_description']; ?></td>
                                <td class="text-right">
                                    <a class="edit" href="#" id="<?php echo $row['holiday_id']; ?>" data-toggle="modal" data-target="#edit_holiday" style="color:black" ><i class="fa fa-pencil m-r-5"></i></a>
                                    <a class="delete" href="#" data-toggle="modal" data-target="#delete_holiday" id="<?php echo $row['holiday_id']; ?>"  role="button" style="color:black"  ><i class="fa fa-trash-o m-r-5"></i> </a>
                                </td>
                            </tr>
                        <?php } ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- /Page Content -->
    <?php include 'footer.php'; ?>
    <!-- Add Holiday Modal -->
    <div class="modal custom-modal fade" id="add_holiday" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="holiday_name" type="text">
                    </div>
                    <div class="form-group">
                        <label>Holiday Date <span class="text-danger">*</span></label>
                        <div><input class="form-control" id="holiday_date" type="date"></div>
                    </div>

                    <div class="form-group">
                        <label>Holiday Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="holiday_description" type="text"></textarea>
                    </div>
                    <div class="submit-section">

                        <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $_SESSION['admin_id']; ?>">
                        <button class="btn btn-primary submit-btn" onclick="addholiday()">Submit</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /Add Holiday Modal -->
    <!-- Edit Holiday Modal -->
    <div class="modal custom-modal fade" id="edit_holiday" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Holiday Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="h_name" type="text">
                            <input class="form-control" id="h_id" type="hidden">
                        </div>
                        <div class="form-group">
                            <label>Holiday Date <span class="text-danger">*</span></label>
                            <div><input class="form-control" id="h_date" type="date"></div>

                        </div>
                        <div class="form-group">
                            <label>Holiday Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="h_desc" type="text"></textarea>
                        </div>
                        <div class="submit-section">
                            <button id="edit_data" class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Holiday Modal -->

    <script type="text/javascript">

        $(document).on('click', '.edit', function () {
            var holiday_id = $(this).attr("id");
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {holiday_id: holiday_id, action: 'h_view'},
                datatype: "json",
                success: function (data) {
                    var user = JSON.parse(data);
                    $('#h_id').val(user.holiday_id);
                    $('#h_name').val(user.holiday_name);
                    $('#h_date').val(user.holiday_date);
                    $('#h_desc').val(user.holiday_description);
                    $('#edit_holiday').modal('show');
                }
            });
        });

         // function for delete data
        $(document).on('click', '.delete', function () {
            var holiday_id = $(this).attr("id");
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
                            type: 'POST',
                            data: {h_id: holiday_id, action: 'holiday_delete'},
                            success: function (data) {
                                swal({text:"Holiday Delete successfully",
                                    timer: 2000});
                                window.location.href = 'holidays.php';
                            }
                        });
                    } else {
                        swal("Your record is safe!");
                    }
                });
        });

        $(document).on('click', '#edit_data', function (event) {
            event.preventDefault();
            var name = document.getElementById('h_name').value;
            var desc = document.getElementById('h_desc').value;
            var date = document.getElementById('h_date').value;
            var id = document.getElementById('h_id').value;
            if (val_name(name))
            {
                if (val_date(date))
                {
                    if (val_desc(desc))
                    {
                        $.ajax({
                            url: '../control.php',
                            type: 'POST',
                            data: {id: id, name: name, date: date, desc: desc, action: 'edit_data'},
                            success: function (data)
                            {
                                window.location.href = 'holidays.php';
                            }
                        });
                    }
                }
            }
        });
        function val_name(val) {
            if (val == '') {
                swal({title:'Please Write Holiday Name'});
                return false;
            } else {
                return true;
            }
        }
        function val_desc(val) {
            if (val == '') {
                swal({title:'Please Write Holiday Description'});
                return false;
            } else {
                return true;
            }
        }
        function val_date(val) {
            if (val == '') {
                swal({title:'Please Select Holiday Date'});
                return false;
            } else {
                return true;
            }
        }

        function addholiday()
        {
            var holiday_name = document.getElementById('holiday_name').value;
            var holiday_date = document.getElementById('holiday_date').value;
            var holiday_description = document.getElementById('holiday_description').value;
            var admin_id = document.getElementById('admin_id').value;
            if (val_name(holiday_name)) {
                if (val_date(holiday_date)) {
                    if (val_desc(holiday_description)) {
                        $.ajax({
                            url: "../control.php",
                            type: "POST",
                            data: {
                                holiday_name: holiday_name,
                                holiday_date: holiday_date,
                                holiday_description: holiday_description,
                                admin_id: admin_id,
                                action: 'addholiday'},
                            datatype: "html",
                            success: function (data)
                            {
                                $('#edit_holiday').modal('hide');
                                window.location.href = 'holidays.php';
                            }
                        });
                    }
                }
            }
        }
            $(document).ready( function () {
            $('#holiday').DataTable({
                // ordering :false,
                select: true,
                paging:   true,


            });
        });
        
 
    </script>
    <?php
} else {
    header("Location:../login.php");
}
?>