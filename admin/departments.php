<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Department';
    include'admin_header.php';
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
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_department"><i class="fa fa-plus"></i> Add Department</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div>
                    <table id="department" class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 30px;"><b>No</b></th>
                                <th class="text-center"><b>Department Name</b></th>
                                <th class="text-right"><b>Action</b></th>
                            </tr>
                        </thead>
                        <tbody id="view_data">
                            <?php 
                                        $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ORDER BY departments_id DESC");
                                            $no = 1;
                                    while ($row = mysqli_fetch_array($sql))
                                    {
                                ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td class="text-center"><?php echo $row['departments_name']; ?></td>
                                <td class="text-right">
                                    <a class="edit_data" href="#" id="<?php echo $row['departments_id']; ?>" style="color:black" title="Edit" data-toggle="modal" data-target="#edit_department" ><i class="fa fa-pencil m-r-5"></i></a>
                                    <?php if($row['used_count'] > 0) {?>
                                        <a href="#"  title="This Data is Used in Other Place You Can not Delete This." style="color:black;cursor: not-allowed"><i class="fa fa-trash-o m-r-5"></i></a>
                                    <?php } else{ ?>
                                        <a class="delete_data" href="#" id="<?php echo $row['departments_id']; ?>" style="color:black" title="Delete" ><i class="fa fa-trash m-r-5"></i></a>
                                    <?php } ?>

                                </td>
                            </tr>
                                    <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    <!-- Add Department Modal -->
    <div id="add_department" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="insert_submit" method="post">
                    <div class="form-group">
                        <label>Department Name <span class="text-danger">*</span></label>
                        <input id="departments_name" name="departments_name" class="form-control" type="text">
                        <input id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" name="admin_id" class="form-control" type="hidden">
                    </div>
                    <div class="submit-section">
                       <button type="submit" id="submit" name="submit" class="btn btn-primary submit-btn"> Add </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Department Modal  -->
    <!-- Edit Department Modal -->
    <div id="edit_department" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit_submit">
                        <div class="form-group">
                            <label>Department Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="" id="d_name" name="d_name" type="text">
                            <input class="form-control" value="" id="d_id" name="d_id" type="hidden">
                        </div> 
                        <div class="submit-section">
                            <input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary submit-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Department Modal -->
    
    <script>
       $(document).ready(function (){

            $('#department').DataTable({

                paging:   true,
                info : true,
                ordering: true
            });
           });
            $(document).on('click', '.edit_data', function(){  
                var department_id = $(this).attr("id");  
                $.ajax({
                     url:"read.php",
                     method:"POST",  
                     data:{d_id:department_id,action:'department_fetch'},  
                     dataType:"json",  
                     success:function(data){  
                          $("#d_id").val(data.departments_id);
                          $("#d_name").val(data.departments_name);
                         
                   }  
                }); 
                }); 
                // Edit Department
            $('#edit_submit').on('submit', function(event){
                event.preventDefault();
                var department_id = $('#d_id').val();  
                var department_name = $('#d_name').val();  
                $.ajax({
                     url:"../control.php",  
                     method:"POST",  
                     data:{d_id:department_id,d_name:department_name,action:'department_edit'},  
                     dataType:"html",  
                     success:function(data){  
                        $('#edit_department').modal('hide');
                         window.location.href='departments.php';
                   }  
                });  
            });
            
            $('#insert_submit').on('submit', function(event){
                event.preventDefault();
                var department_name = $('#departments_name').val();  
                var admin_id = $('#admin_id').val();  
                if(val_department_name(department_name)){
                $.ajax({  
                     url:"../control.php",  
                     method:"POST",  
                     data:{department_name:department_name,admin_id:admin_id,action:'department_add'},  
                     dataType:"html",  
                     success:function(data){  
                        $('#add_department').modal('hide');
                        window.location.href='departments.php';
                   }  
                });  
            }
            });
            function val_department_name(val)
            {
                if(val == ''){
                    swal({title:'Please Write Department'});
                    return false;
                }else{
                    return true;
                }
                    
            }

       // function for delete data
       $(document).on('click', '.delete_data', function () {
           var department_id = $(this).attr("id");
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
                           data: {d_id: department_id,action:'department_delete'},
                           success: function(data)
                           {
                               window.location.href='departments.php';
                           }
                       });
                   } else {
                       swal("Your Recorded is safe!");
                   }
               });

       });
   </script>
<?php 
}else 
{
    header("Location:login.php");
}?>