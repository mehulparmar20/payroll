<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    ?>
	<!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Total Credit Leave</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Total Credit Leave</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="remailning_leave" class="table table-striped custom-table table-nowrap">
                        <thead>
                            <tr>
                                <th><b>Name</b></th>
                                <th><b>Total Leave</b></th>
                                <th class="text-right"><b>Action</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql1 = mysqli_query($conn, "SELECT * FROM total_add_leave  WHERE  admin_id = '" . $_SESSION['admin_id'] . "'  ");
                                 while ($row = mysqli_fetch_array($sql1)) {
                                ?>
                                <tr>  
                                    <td><?php echo $row['emp_name']; ?></td>
                                    <td><?php echo $row['total_leave']; ?></td>
                                    <td class="text-right">
                                        <a class="edit_data" href="#" id="<?php echo $row['e_id']; ?>" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i></a>
                                        <a class="delete" href="#" data-toggle="modal" data-target="#delete_employee"id="<?php echo $row['e_id']; ?>" style="color:black" title="Delete" ><i class="fa fa-trash-o m-r-5"></i></a>
                                    </td>
                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php    include 'footer.php';   ?>
    <!-- edit remainig leave Modal -->
    <div id="edit_leave" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employee Name <span class="text-danger">*</span></label>
                            <input id="name" disabled name="name" class="form-control" type="text">
                            <input id="r_id" name="r_id" class="form-control" type="hidden">
                        </div>
                        <div class="form-group">
                            <label>Total Leave <span class="text-danger">*</span></label>
                            <input id="leave" name="leave" class="form-control" type="number">
                        </div>
                        <div class="submit-section">
                            <button type="submit" id="submit" name="submit" onclick="edit_leave()" class="btn btn-primary submit-btn"> Add </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /end Modal  -->
        
<script>

        $(document).ready( function () {
            datatable();
        });
        $(document).on('click', '.edit_data', function(){  
                var r_id = $(this).attr("id");  
                $.ajax({  
                     url:"read.php",
                     method:"POST",  
                     data:{r_id:r_id,action:'remaining_leave'},  
                     dataType:"json",  
                     success:function(data){  
                          $("#name").val(data.emp_name);
                          $("#leave").val(data.total_leave);
                          $("#r_id").val(data.e_id);
                          $("#edit_leave").modal("show");
                         
                   }  
                }); 
            });
        
        function edit_leave(){
            var name = $("#name").val();
            var leave = $("#leave").val();
            var id = $("#r_id").val();
            $.ajax({  
                     url:"update.php",
                     method:"POST",  
                     data:{r_id:id,name:name,leave:leave,action:'remaining_leave_add'},  
                     dataType:"html",  
                     success:function(data){  
                          swal(data);
                          window.location.href = 'remaining_leave.php'
                         
                   }  
                }); 
        }

        function datatable() {
            
            var table = $('#remailning_leave').DataTable({
            pageLength: 100,
            });

            new $.fn.dataTable.FixedColumns(table);
        }
        
</script>
<?php 

}
else 
{
    header("Location:../index.php");
}
?>