<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['employee'] == 'yes') {
    include 'emp_header.php';
    // echo date_default_timezone_get();
    ?>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Break Report</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Break Report</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <!--<div class="col-sm-6 col-md-3">-->
        <!--    <div class="form-group">-->
        <!--        <input type="month" class="form-control" value="<?php echo date('Y-m'); ?>" id="month"-->
        <!--               onchange="month_change()">-->
        <!--    </div>-->
        <!--    <div class="form-group">-->
        <!--        <b> Data Format : (Date -- Break time)</b>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <select onchange="month_change()" id="month" class="form-control">
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
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <select id="year" onchange="month_change()" class="form-control">
                    <option value="<?php echo date('Y'); ?>">Select Year</option>
                    <?php
                    for ($i = 2019; $i <= 2030; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>
    </div>
    <!-- /Search Filter -->

    <div class="row">
        <div class="col-md-12">
            <div id='view_data'>

            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript">
        //  function for change insert value button
        $(document).ready(function () {
            month_change();
        });
        function month_change() {
            var month = $("#month").val();
            var shiftid = $("#shiftid").val();
            var year = $("#year").val();
            var e_id = $("#e_id").val();
            $.ajax({
                url: "report_break.php",
                type: "POST",
                data: {month: month,shiftid:shiftid,year:year,e_id: e_id},
                success: function (data) {
                    $('#view_data').html(data);
                    datatable();
                }
            });
        }
        function datatable() {
            var table = $('#break_report').DataTable({
                scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                fixedColumns: true,
            });
            new $.fn.dataTable.FixedColumns(table);
        }
    </script>

    <?php
} else {
    header("Location:../index.php");
}
