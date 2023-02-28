<?php
session_start();
if ($_SESSION['employee'] == 'yes' && $_SESSION['salary'] == 'yes') {
    include '../dbconfig.php';
    include'emp_header.php';
    ?>
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Employee Salary</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Salary</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <!-- Search Filter -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <select id="month" onchange="month_change()" class="form-control floating">
                        <option value="<?php echo date('n'); ?>">Select Month</option>
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
            <div class="col-sm-3">
                <div class="form-group">
                    <select id="year" onchange="year_change()" class="form-control">
                        <option value="<?php echo date('Y'); ?>">Select Year</option>
                        <?php for ($i = 2019; $i < 2030; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>

                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control"><b>Salary of : <span id="month_year" style="color: black;"></span></b></label>
                </div>
            </div>
        </div>
        <!-- /Search Filter -->
        <div class="row">
            <div class="col-md-12">
                <div id="view-salary">

                </div>
            </div>
        </div>
        <!-- /Search Filter -->
    </div>
    <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    <?php
    include 'footer.php';?>
<script>
    $(document).ready(function(){
        month_change();
    });
    // When Month Chnage
    function month_change()
    {
        var month = $("#month").val();
        var year = $("#year").val();
        var admin_id = $("#admin_id").val();
        var e_id = $("#e_id").val();
        var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
        $.ajax({
            url:"fetch.php",
            type:"POST",
            data:{month:month,year:year,admin_id:admin_id,e_id:e_id,action:'view_salary'},
            success:function(data)
            {
                $('#view-salary').html(data);
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });
            }
        });
    }
    // When Year Chnage
    function year_change()
    {
        var month = $("#month").val();
        var year = $("#year").val();
        var admin_id = $("#admin_id").val();
        var e_id = $("#e_id").val();
        var  month_names = ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
        $.ajax({
            url:"fetch.php",
            type:"POST",
            data:{month:month,year:year,admin_id:admin_id,e_id:e_id,action:'view_salary'},
            success:function(data)
            {
                $('#view-salary').html(data);
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });
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
?>