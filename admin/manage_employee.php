<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Employee History';
    include'admin_header.php';
    include '../dbconfig.php';
?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <div class="row filter-row">
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" name="search_box" id="search_box" class="form-control" placeholder="Search Here" />
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="loading_data(1)" class="custom-select" id="select_row" name="select_row">
                    <option value="10" >Show Entries</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="loading_data(1)" class="custom-select" id="column" name="column">
                    <option value="employee.e_id" selected>Show Entries</option>
                    <option value="employee.join_date">Join Date</option>
                    <option value="employee.employee_status">Status</option>
                    <option value="employee.e_benefits">Benefits</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="loading_data(1)" class="custom-select" id="sorting" name="sorting">
                    <option value="ASC" selected>Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="manage">
            </div>
        </div>
    </div>
    <!-- Active Employee Modal -->
        <div class="modal custom-modal fade" id="active_employee" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Active Employee</h3>
                            <p>Are you sure want to Active Employee?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="active_employee()" class="btn btn-primary continue-btn">Active</a>
                                    <input type="hidden" value="" id="active_val">
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Active Employee Modal -->
    <!-- Deactive Employee Modal -->
        <div class="modal custom-modal fade" id="deactive_employee" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Deactivate Employee</h3>
                            <p>Are you sure want to Deactivate Employee?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="deactive_employee()" class="btn btn-primary continue-btn">Deactivate</a>
                                    <input type="hidden" value="" id="deactive_val">
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Deactive Employee Modal -->
    <!-- Active Employee Modal -->
        <div class="modal custom-modal fade" id="on_benifits" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Benefits ON</h3>
                            <p>Are you sure want to Benefits ON?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="on_benifits()" class="btn btn-primary continue-btn">Active</a>
                                    <input type="hidden" value="" id="on_ben_val">
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Active Employee Modal -->
        <!-- Deactive Employee Modal -->
        <div class="modal custom-modal fade" id="off_benifits" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Benefits OFF</h3>
                            <p>Are you sure want to Benefits OFF?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" onclick="off_benifits()" class="btn btn-primary continue-btn">Deactivate</a>
                                    <input type="hidden" value="" id="off_ben_val">
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Deactive Employee Modal -->
    <?php include 'footer.php'; ?>

    
    <script>

        $(document).ready( function () {
            loading_data(1);
            // function loading_data(page, query = '')
            // {
            //     var admin_id = $("#admin_id").val();
            //     var select_row = $("#select_row").val();
            //     var column = $("#column").val();
            //     var sorting = $("#sorting").val();
            //     $.ajax({
            //         url:"manage_emp.php",
            //         method:"POST",
            //         data:{page:page,select_row:select_row,column:column,sorting:sorting,admin_id:admin_id,query:query},
            //         success:function(data)
            //         {
            //             $('#manage').html(data);
            //         }
            //     });
            // }

            $(document).on('click', '.page-link', function(){
                var page = $(this).data('page_number');
                var query = $('#search_box').val();
                loading_data(page, query);
            });

            $('#search_box').keyup(function(){
                var query = $('#search_box').val();
                loading_data(1, query);
            });
        });

        function loading_data(page, query = '')
        {
            var admin_id = $("#admin_id").val();
            var select_row = $("#select_row").val();
            var column = $("#column").val();
            var sorting = $("#sorting").val();
            $.ajax({
                url:"manage_emp.php",
                method:"POST",
                data:{page:page,select_row:select_row,column:column,sorting:sorting,admin_id:admin_id,query:query},
                success:function(data)
                {
                    $('#manage').html(data);
                }
            });
        }
            $(document).on('click', '.active_emp', function(){  
                var id = $(this).attr("id");  
                $("#active_val").val(id); 
            });
                  function active_employee()
                  {
                      var id = $("#active_val").val(); 
                      $.ajax({
                         url: 'action.php',
                         type: 'POST',
                         data: {e_id: id,action:'active'},
                         success: function(data) {
                             $("#active_employee").modal('hide');
                            swal( "Employee Active Successfully.");
                             loading_data(1);
                         }
                      });
                  }
        
         $(document).on('click', '.deactive', function(){  
            var id = $(this).attr("id");
            $("#deactive_val").val(id);
        });
              function deactive_employee()
              {
                  var id = $("#deactive_val").val(); 
                    $.ajax({
                       url: 'action.php',
                       type: 'POST',
                       data: {e_id: id,action:'deactive'},
                       success: function(data) {
                           $("#deactive_employee").modal('hide');
                            swal("Employee deactivated sucessfully.");
                           loading_data(1);
                       }
                    });
              }
            $(document).on('click', '.on_benifits', function(){  
                var id = $(this).attr("id");  
                $("#on_ben_val").val(id); 
            });
                  function on_benifits()
                  {
                      var id = $("#on_ben_val").val(); 
                      $.ajax({
                         url: 'action.php',
                         type: 'POST',
                         data: {e_id: id,action:'on_benifits'},
                         success: function(data) {
                             $("#on_benifits").modal('hide');
                            swal( "Benefits ON Successfully.");
                             loading_data(1);
                         }
                      });
                  }
        
         $(document).on('click', '.off_benifits', function(){  
            var id = $(this).attr("id");
            $("#off_ben_val").val(id);
        });
              function off_benifits()
              {
                  var id = $("#off_ben_val").val(); 
                    $.ajax({
                       url: 'action.php',
                       type: 'POST',
                       data: {e_id: id,action:'off_benifits'},
                       success: function(data) {
                           $("#off_benifits").modal('hide');
                           swal("Benefits OFF Successfully.");
                           loading_data(1);
                       }
                    });
              }
          
    </script>
    <?php
} else {
    header("Location:../login.php");
}