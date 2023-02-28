<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = 'Break Report';
    include'admin_header.php';
    ?>			
    <style>
        /*th:first-child, td:first-child { position:sticky; left:0px; background-color:#FFFFFF; }*/
    </style>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
        <style>
            .DTFC_LeftBodyLiner {
            overflow-x: hidden;
            }
        </style>
    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group">
                <input type="hidden" id="shift_id" value="<?php echo $_SESSION['shift_id']; ?>">
                <select id="month" onchange="month_change()" class="form-control"> 
                    <option value="<?php echo date("n"); ?>">--Month--</option>
                    <option value="1">Jan</option>
                    <option value="2">Feb</option>
                    <option value="3">Mar</option>
                    <option value="4">Apr</option>
                    <option value="5">May</option>
                    <option value="6">Jun</option>
                    <option value="7">Jul</option>
                    <option value="8">Aug</option>
                    <option value="9">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>
            </div>
        </div>
        <div id="hiddiv" ></div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group">
                <select id="year" onchange="year_change()" class="form-control"> 
                    <option value="<?php echo date("Y"); ?>" >--Year--</option>
                    <?php for($i = 2019;$i <= 2030; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>
        <div class="col-sm-6 col-md-3">  
            <div class="form-group">
                <lable class="form-control" id="month_year" ></lable>
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
         month_change();
         setInterval(function(){
            $('#hiddiv').load('timeout.php');
            },1200000);

    });
                   function month_change(){
                           var month = $("#month").val();
                           var year = $("#year").val();
                           var shift_id = $("#shift_id").val();
                           var admin_id = $("#admin_id").val();
                           var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                           document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
                                $.ajax({
                                   url:"report_break.php",
                                   type:"POST",
                                   data:{month:month,year:year,admin_id:admin_id,shift_id:shift_id},
                                   success:function(data)
                                   {    
                                        $('#view_data').html(data);
                                        datatable();
                                   }
                               });
                            }
                   function year_change(){
                           var year = $("#year").val();
                           var month = $("#month").val();
                           var shift_id = $("#shift_id").val();
                           var admin_id = $("#admin_id").val();
                            var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                           document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
                               $.ajax({
                                   url:"report_break.php",
                                   type:"POST",
                                   data:{month:month,year:year,admin_id:admin_id,shift_id:shift_id},
                                   success:function(data)
                                   {
                                       $('#view_data').html(data);
                                       datatable();
                                   }
                               });
                            }
                            function datatable()
                            {
                                    $("#attendance").DataTable({
                                        scrollY: 400,
                                        scrollX: 'auto',
                                        ordering: false,
                                        pageLength: 100,
                                        fixedColumns:   true
                                    });
                            }
                                
               </script>

<?php 
}
else
{
    header("Location:../login.php");
}
