<?php
ini_set('session.gc_maxlifetime', 7200);
session_start();
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include'admin_header.php';
    ?>			
    <!-- Page Header -->
    <style>
        th:first-child, td:first-child { position:sticky; left:0px; background-color:grey; }
    </style>
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Employee Report</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employee Report</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group">
                <input type="date" onchange="new_change()" id="search_date" class="form-control"> 
            </div>
        </div>
        <div class="col-sm-6 col-md-4"> 
             <div class="form-group">
                <select class="form-control" id="select_staff" name="select_staff" >
                    <?php
                    //show employees on add salary form from employees table
                    $query = mysqli_query($conn, "select e_firstname,e_lastname,emp_cardid from employee");
                    echo "<option disabled='' selected=''>Select Employees</option>";
                    while ($selectstaff = mysqli_fetch_array($query)) {
                        echo "<option value='" . $selectstaff['e_firstname'] . "'>" . $selectstaff['e_firstname'] . " " . $selectstaff['e_lastname'] . "</option>";
                        $empcardid = $selectstaff['emp_cardid'];
                    }
                    ?>
                </select>
            </div>
        </div>     
    </div>
    <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
                <div id="view_data">
               
                </div>
            </div>
        </div>
        


    <?php include 'footer.php';
 ?>
<script type="text/javascript">
   $(document).ready(function(){
         c_change();
         
   });
            
                   function c_change(){
                           var day = $("#c_date").val();
                           var employee = $(#"select_staff").val();
                           var admin_id = $("#admin_id").val();
                            $("#date_view").val(day);
                                $.ajax({
                                   url:"attendance_view.php",
                                   type:"POST",
                                   data:{day:day,admin_id:admin_id,employee:employee},
                                   success:function(data)
                                   {
                                        $('#view_data').html(data);
                                       datatable();
                                   }
                               });
                            }
                   function new_change(){
                           var day = $("#search_date").val();
                           var admin_id = $("#admin_id").val();
                           $("#date_view").val(day);
                               $.ajax({
                                   url:"attendance_view.php",
                                   type:"POST",
                                   data:{day:day,admin_id:admin_id},
                                   success:function(data)
                                   {
                                       $('#view_data').html(data);
                                       datatable();
                                   }
                               });
                            }
                  
                            // view delete
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            //            alert(id);

            if (confirm('Are you sure want to decline for this leave?'))
            {
                $.ajax({
                    url: 'delete.php',
                    type: 'post',
                    data: {b_id: id, action: 'break_delete'},
                    //                   alert(are you Sure???);
                    error: function () {
                        alert('Something is wrong');
                    },
                    success: function (data) {
                        $("#" + id).remove();
                        //                        alert(data);  
                        alert("Employee Delete successfully");
                        window.location.href = 'employees.php';
                    }
                });
            }
        });
            
            function datatable()
            {
                    var table = $('#attendance').DataTable( {
                        
                        scrollY:        400,
                        scrollX:        true,
                        // scrollCollapse: true,
                        // scroller:       true,
                        // fixedColumns:   true,
                    } );
                    new $.fn.dataTable.FixedColumns( table );
            }
                            
               </script>

<?php 
}
else
{
    header("Location:../login.php");
}
