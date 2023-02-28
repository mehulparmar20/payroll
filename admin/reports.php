<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    include '../dbconfig.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Reports</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                
                <div class="view-icons">
                    <a href="break_report.php" title="Monthly View" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                    
                </div>
                <div class="view-icons">
                    <a href="leave_report.php" title="Monthly View" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3">  
            <div class="form-group">
                <input type="text" id="emp_search" placeholder="Search Employee" class="form-control floating">
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group">
                <select name="depart_search" id="depart_search" class="form-control"> 
                    <option>Select Department</option>
                        <?php
                            $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                        <option><?php echo $row['departments_name']; ?></option>
                            <?php } ?>
                </select>
               
            </div>
        </div>
    </div>
    <!-- Search Filter -->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table">
                    <thead>
                        <tr>
                            <th><center><b>Name</b></center></th>
                            <th><center><b>Employee ID</b></center></th>
                            <th><center><b>Email</b></center></th>
                            <th class="text-nowrap"><center><b>Break Reports</b></center></th>
                            <th class="text-right no-sort"><b>Leave Reports</b></th>
                        </tr>
                    </thead>
                    <tbody id="view_data">
                       <?php  
                        $limit = 10;  
                            if (isset($_GET["page"])) 
                            {  
                                $pageno  = $_GET["page"];  
                            }  
                            else 
                            {  
                                $pageno=1;  
                            }   
  
                            $start_from = ($pageno-1) * $limit;   
                        $sql1 = mysqli_query($conn, "SELECT * FROM employee  WHERE delete_status = '0' and admin_id = ".$_SESSION['admin_id']." LIMIT $start_from, $limit ");
                            
                                while($row = mysqli_fetch_array($sql1))
                                {
                        ?>
                       <tr>  
                                        <td>
                                            <center><h2 class="table-avatar">
                                            <a href="profile.php?id=<?php echo $row['e_id']; ?>" class="avatar"><img alt="" src="app/img/profiles/avatar-02.jpg"></a>
                                            <a href="profile.php?id=<?php echo $row['e_id']; ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname']; ?><span><?php echo $row['department']; ?></span></a>
                                            </h2></center>
                                        </td>
                                    <td><center><?php echo $row['emp_cardid']; ?></center></td>
                                    <td><center><?php echo $row['e_email']; ?></center></td>
                                    <td class="text-nowrap">
                                    <center><a class="break_view" href="#" data-toggle="modal"  data-target="#break_report" id="<?php echo $row['emp_cardid']; ?>" style="color:black" title="View" ><i class="fa fa-eye"></i></a></center>
                                    </td>    
                                    <td >
                                    <center><a class="leave_view" href="#" data-toggle="modal"  data-target="#leave_report" id="<?php echo $row['emp_cardid']; ?>" style="color:black;" title="View" ><b><i class="fa fa-eye"></i></b></a></center>
                                    </td>
                               </tr> 
                                <?php } ?>
                    </tbody>
                </table>
                <div style="margin-left:80%">
                        <ul class="pagination ">
                            <?php   
                          
                                    $re = mysqli_query($conn,"SELECT COUNT(*) FROM employee");   
                                    $row = mysqli_fetch_row($re);   
                                    $table = $row[0];   

                                    // Number of pages required. 
                                    $t_pages = ceil($table / $limit);   
                                    $pagLink = "";                         
                                    for ($i=1; $i<=$t_pages; $i++) { 
                                      if ($i==$pageno) 
                                      { 
                                          $pagLink .= '<li class="page-item active"><a class="page-link"  href="employees.php?page='.$i.'">'.$i.'</a></li>'; 
                                      }             
                                      else  
                                      { 
                                          $pagLink .= '<li class="page-item"><a class="page-link" href="employees.php?page='.$i.'">'.$i.'</a></li>';   
                                      } 
                                    }   
                                    echo $pagLink;   
                        ?> 
                                
                        </ul>
                    </div> 

            </div>
        </div>
    </div>

    </div>

    </div>
    <!-- /Page Wrapper -->
<?php  include 'footer.php';  ?>

   <!-- Leave report -->
<div id="leave_report" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                       <div class="form-group">
                           <select class="form-control" name="month"  id="month">
                                    <option>Select Month</option>
                                    <?php
                                        for($i = 1; $i <= 12; $i++ ){
                                            ?>
                                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" onchange="leave_report()" name="year"  id="year">
                                    <option>Select Year</option>
                                    <?php
                                        for($i = 2019; $i <= 2030; $i++ ){
                                            ?>
                                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                        <?php } ?>
                            </select>
                            <input class="form-control" required id="emp_id2"  name="emp_id2" type="hidden">
                        </div>
                    </div>
                </div>
                    <div class="card punch-status">
                            <div class="card-body">
                                <h5 class="card-title">Timesheet <small id="viewmonth" class="text-muted"></small></h5>
                                    <div class="stats-box">
                                        <p>Total Days Absent in Month:</p>
                                        <h5 id="leaves"></h5>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
   
<!--   break Report-->
<div id="break_report" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Break Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input class="form-control datepicker" onchange="report()" required id="date"  name="date" type="date">
                            <input class="form-control" required id="emp_id"  name="emp_id" type="hidden">
                        </div>
                    </div>
                </div>
                <div class="card punch-status">
                    <div class="card-body">
                        <h5 class="card-title">Timesheet <small class="text-muted" id="dateview"></small></h5>
                            <div class="statistics">
                                <div id="status" class="stats-box">
                                        <p>Total Break Time</p>
                                        <h6 id="break_time"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
            $(document).ready(function (){
                
                function view_data(search)
                {
                    var admin_id = document.getElementById('admin_id').value; 
//                    alert(admin_id);
                    $.ajax({
                        url:'fetch.php',
                        type:'POST',
                        data:{search:search,admin_id:admin_id,action:'search_depart'},
                        success:function(data)
                        {
                            $('#view_data').html(data);
                        }
                    });
                }
                function view_emp(search)
                {
                    var admin_id = document.getElementById('admin_id').value; 
//                    alert(admin_id);
                    $.ajax({
                        url:'fetch.php',
                        type:'POST',
                        data:{search:search,admin_id:admin_id,action:'search_emp'},
                        success:function(data)
                        {
                            $('#view_data').html(data);
                        }
                    });
                }
                
                $('#depart_search').change(function(){
		var search = $(this).val();
//                alert(10);
		if(search != '')
		{
                    view_data(search);
		}
		else
		{
                    view_data();			
		}
	});
                $('#emp_search').keyup(function(){
		var search = $(this).val();
                    view_emp(search);
		
	});
            });
            $(document).on('click', '.break_view', function(){  
                var employee_id = $(this).attr("id");  
                $("#emp_id").val(employee_id);  
//                alert(employee_id);
                });
                
                function report()
                {
                    var date = $("#date").val();  
                    var employee_id = $("#emp_id").val();  
                    alert(employee_id);
                    document.getElementById("dateview").innerHTML = date;  
                $.ajax({  
                     url:"fetch.php",  
                     method:"POST",  
                     data:{emp_id:employee_id,date:date,action:'break_time'},  
                     dataType:"html",  
                     success:function(data){  
//                         alert(data);
                         if(data < 3)
                         {
                            document.getElementById("break_time").style.color = "green";
                            document.getElementById("status").style.border = "thick solid #008000";
//                            document.getElementById("status1").style.border = "thick solid #008000";
                         }
                         else
                         {
                             document.getElementById("break_time").style.color = "red";
                             document.getElementById("status").style.border = "thick solid #FF0000";
//                             document.getElementById("status1").style.border = "thick solid #FF0000";
                         }
                          
                         document.getElementById("break_time").innerHTML = data;  
                   }  
                });  
     }  
      $(document).on('click', '.leave_view', function(){  
          var id = $(this).attr("id");  
           $("#emp_id2").val(id); 
//            alert(id);
           });
                
            function  leave_report()
            {
                var month = $("#month").val();  
                var year = $("#year").val();  
                var emp_id = $("#emp_id2").val();  
//                document.getElementById("dateview").innerHTML = date;  
                $.ajax({
                   url: 'fetch.php',
                   type: 'post',
                   data: {emp_id: emp_id,month:month,year:year,action:'leave_report'},
                   success: function(data) 
                   {
                        if(data < 3)
                         {
                            document.getElementById("leaves").style.color = "green";
                            document.getElementById("status").style.border = "thick solid #008000";
//                            document.getElementById("status1").style.border = "thick solid #008000";
                         }
                         else
                         {
                             document.getElementById("leaves").style.color = "red";
                             document.getElementById("status").style.border = "thick solid #FF0000";
//                             document.getElementById("status1").style.border = "thick solid #FF0000";
                         }
                          
                         document.getElementById("leaves").innerHTML = data; 
                   }
                });
            }
       
    
     
           </script>
           <?php }
           else
           { 
               header("Location:../login.php");
           }