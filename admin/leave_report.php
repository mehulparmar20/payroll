<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include'admin_header.php';
    ?>			
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Leaves Report</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Total leaves</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-2"> 
            <div class="form-group">
                <select id="month" onchange="change_month()" name="month" class="form-control"> 
                    <option value="<?php echo date('n'); ?>" >--Month--</option>
                    <option value="1" >Jan</option>
                    <option value="2" >Feb</option>
                    <option value="3" >Mar</option>
                    <option value="4" >Apr</option>
                    <option value="5" >May</option>
                    <option value="6" >Jun</option>
                    <option value="7" >Jul</option>
                    <option value="8" >Aug</option>
                    <option value="9" >Sep</option>
                    <option value="10" >Oct</option>
                    <option value="11" >Nov</option>
                    <option value="12" >Dec</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-2"> 
            <div class="form-group">
                <select id="year" onchange="change_year()" name="year" class="form-control"> 
                    <option value="<?php echo date('Y'); ?>">--Year--</option>
                     <?php
                        for($i = 2019; $i <= 2030; $i++ ){
                            ?>
                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                        <?php } ?>
                </select>

            </div>
        </div>
    
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group">
                <label class="form-control" ><b>Leave Reports From:   </b><span id="reoprt-month" style="color: black;font-size: 18px"></span> </label>
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



    <?php include 'footer.php';
 ?>
<script type="text/javascript">
    $(document).ready(function (){
        
  	change_month();
    });
    function change_month()
    {
        var year = $("#year").val();
        var month = $("#month").val();
        var admin_id = $("#admin_id").val();
        var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        document.getElementById("reoprt-month").innerHTML = month_names[month] + "-" + year;
        $.ajax({
            url:'report_leave.php',
            type:'POST',
            data:{year:year,month:month,admin_id:admin_id},
            success:function(data)
            {
                $('#view_data').html(data);
                $("#leave_report").DataTable();
            }
        });
    }
   function change_year(){
       
		var year = $("#year").val();
		var month = $("#month").val();
		var admin_id = $("#admin_id").val();
                var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                document.getElementById("reoprt-month").innerHTML = month_names[month] + "-" + year;
		$.ajax({
                    url:'report_leave.php',
                    type:'POST',
                    data:{year:year,month:month,admin_id:admin_id},
                    success:function(data)
                    {
                        $('#view_data').html(data);
                        $("#leave_report").DataTable();
                    }
                });			
		
	}
               </script>

<?php 
}
else
{
    header("Location:../login.php");
}
