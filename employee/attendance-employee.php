<?php
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include'emp_header.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Attendance</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-6 flex">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title">Timesheet <small class="text-muted"></small></h5>
                    <br>
                    <div id="punch-date" class="punch-det">
                        
                    </div>
                    <br>
                    <br>
                    <div class="statistics">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                
                            </div>
                            <div class="col-md-8 text-center">
                                <div class="stats-box">
                                    <p>Break</p>
                                    <div id="break"> <h6></h6> </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                
                            </div>
                        </div>
                    </div><br>
                    <br>
                    <br>
                    <br>
                    <div class="punch-btn-section">
                        <input type="date" value="<?php echo date("Y-m-d"); ?>"  onchange="att_change()" id="att_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card recent-activity">
                <div class="card-body">
                    <h5 class="card-title">Today Activity</h5>
                    <ul id='break_info' class="res-activity-list">

                    </ul>
                </div>
            </div>
        </div>
            
    </div>
            <!-- Search Filter -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="month" value="<?php echo date("Y-m"); ?>"  onchange="month_change()" id="month" class="form-control">
                </div>    
            </div>
        </div>
        <!-- /Search Filter -->
    <div class="row">    
        <div class="col-md-12">
            <div id="view_data" class="table-responsive">
                
            </div>
        </div>
    </div>
    
    <!-- /Page Content -->

<?php include 'footer.php'; ?>
    <script>
       $(document).ready(function(){
            month_change();
            att_change();
        });
           // When Month Chnage 
        function month_change()
        {
            var month = $("#month").val();
            var admin_id = $("#admin_id").val();
             $.ajax({
                url:"data.php",
                type:"POST",
                data:{month:month,admin_id:admin_id},
                success:function(data)
                {
                    $('#view_data').html(data);
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                      });
                }
            });
        }

        // When Year Chnage 
        function att_change()
        {
            var day = $("#att_date").val();
            if(day == ''){
                 var day = $("#def_date").val();
            }
            var admin_id = $("#admin_id").val();
            var e_id = $("#e_id").val();
            
                    $("#overlay").show();
             $.ajax({
                url:"fetch.php",
                type:"POST",
                data:{day:day,admin_id:admin_id,e_id:e_id,action:'att_info'},
                success:function(data)
                {
                    $("#overlay").hide();
                    var json = JSON.parse(data);
                    $("#break").html(json.total_break)
                    $("#punch-date").html(json.attendance)
                    $("#break_info").html(json.break_time)
                }
            });
        }
        
    
    </script>
<?php
}
else
{
   header("Location:../login.php");
}?>