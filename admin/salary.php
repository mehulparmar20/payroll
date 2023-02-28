<?php
ob_start();
//session start
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes' && $_SESSION['accounting'] == 'yes') {
    //include header.php file for header
    $page = 'Salary';
    include 'admin_header.php';
    //database connection
    ?>
    <style>
    .DTFC_LeftBodyLiner{
        overflow-x: hidden;
    }
    </style>
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
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_salary"><i class="fa fa-plus"></i>
                    Add Salary</a>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#export_salary"><i
                            class="fa fa-download"></i> Export Salary</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
        <!-- Leave Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-info">
                <?php
                $sql1 = mysqli_query($conn, "SELECT SUM(e_salary) FROM `employee` WHERE employee_status = '1' and delete_status = '0' and admin_id = " . $_SESSION['admin_id'] . " ");
                $val = mysqli_fetch_assoc($sql1);
                
                ?>
                <h3 style="color: black"><b><?php echo number_format((float)$val['SUM(e_salary)'],2); ?></b></h3>
                <span style="color: black"><b>Estimate Salary</b></span>
            </div>
        </div>
    </div>
    <!-- /Leave Statistics -->
    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-2">
            <div class="form-group">
                <select onchange="month_change()" id="month" class="form-control">
                    <option value="<?php echo date('n', strtotime('-1 month', time())); ?>">Select Month</option>
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
                <select id="year" onchange="year_change()" class="form-control">
                    <option value="<?php if(date('m') != 1) { echo date('Y'); }else{ echo date('Y') - 1; } ?>">Select Year</option>
                    <?php
                        $year = date('Y') + 5;
                        for ($i = 2019; $i <= $year; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <label class="form-control"><b>Salary Report Month : </b><b><span id="month_year"
                                                                                  style="color: black;font-size: 18px"></span></b></label>
            </div>
        </div>
    </div>
    <!-- /Search Filter -->
    <!-- Main contain Start -->
    <div class="row">
        <div class="col-md-12">
            <div id="view_data">

            </div>
        </div>
    </div>
    <!-- /Page Content -->
    <?php include 'footer.php'; ?>
    <!-- Add Salary Modal -->
    <div id="add_salary" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Staff Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Month</label>
                                <select id="sal_month" class="form-control">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Year</label>
                                <select id="sal_year" class="form-control">
                                    <option value="<?php echo date('Y'); ?>">Select Year</option>
                                    <?php
                                    $year = date('Y') + 5;
                                    for ($i = 2019; $i <= $year; $i++) {
                                        ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-success" onclick="add_salary()" name="submit" id="submit">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Salary Modal -->
    <div id="export_salary" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Staff Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="control.php">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Month</label>
                                    <select id="e_sal_month" name="e_sal_month" class="form-control">
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
                                    <input type="hidden" id="admin_id" name="admin_id"
                                           value="<?php echo $_SESSION['admin_id']; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select id="e_sal_year" name="e_sal_year" class="form-control">
                                        <option value="<?php echo date('Y'); ?>">Select Year</option>
                                        <?php
                                        for ($i = 2019; $i <= 2030; $i++) {
                                            ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Bank Name <span
                                                class="text-muted">(Example BANK Name - Branch Name)</span></label>
                                    <input type="text" name="bank_name" class="form-control" id="bank_name"
                                           placeholder="Example ICICI BANK - Anand">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <input type="submit" value="PDF" class="btn btn-secondary" name="pdf" id="pdf">
                            <input type="submit" value="CSV" class="btn btn-secondary" name="csv" id="csv">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="ajax-loader"><img src='app/img/loder.gif' class="img-responsive"/><br>Loading..</div>
    <!-- Edit Salary Modal -->
    <div id="edit_salary" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Staff Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view_field">

                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Salary Modal -->


    <script type="text/javascript">

        function month_change() {
            var month = $("#month").val();
            var year = $("#year").val();
            var admin_id = $("#admin_id").val();
            var month_names = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
            $.ajax({
                url: 'read.php',
                type: 'POST',
                dataType: "html",
                data: {admin_id: admin_id, month: month, year: year, action: 'fetch_salary'},
                success: function (data) {
                    $("#view_data").html(data);
                    datatable();
                }
            });
        }

        function year_change() {
            var month = $("#month").val();
            var year = $("#year").val();
            var admin_id = $("#admin_id").val();
            var month_names = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById("month_year").innerHTML = month_names[month] + "-" + year;
            $.ajax({
                url: 'read.php',
                type: 'POST',
                dataType: "html",
                data: {admin_id: admin_id, month: month, year: year, action: 'fetch_salary'},
                success: function (data) {
                    $("#view_data").html(data);
                    datatable();
                }
            });
        }
        
        function makeDecision(tableid,obj){
            var table = document.getElementById(tableid);
            for (var j = 0; j < table.rows.length; j++) {
                var value = table.rows[j].cells[0].getAttribute("data-id");
                if (typeof value !== "undefined") {
                    if (value == obj.salary_id) {
                        table.deleteRow(j);
                        let row = createRow(obj);
                        let addrow = table.insertRow(j);
                        addrow.innerHTML = row;
                        break;
                    }
                }
            }
        }
        
        function createRow(obj){
            var row =`<tr data-id='${obj.salary_id}' >
                    <td data-id='${obj.salary_id}' >${obj.emp_name}</td>
                    <td><center>${obj.basic}</center></td>
                    <td><center>${obj.working_days}</center></td>
                    <td><center>${obj.present_days}</center></td>
                    <td><center>${obj.absent_days}</center></td>`;
            if(obj.pf != 'not used') {
                row += `<td><center>${obj.pf }</center></td>`;
            }
            
    
            row +=`<td ><center>${obj.over_time }</center></td>
                  <td><center>${obj.incentive }</center></td>
                  <td><center>${obj.e_leave }</center></td>
                  <td><center>${obj.break_violation }</center></td>
                  <td><center>${obj.late_fine }</center></td>
                  <td><center>${obj.net_salary }</center></td>
                  <td>
                      <a href="#" id="${obj.salary_id }" class="update" data-toggle="modal" data-target="#edit_salary"><i class="fa fa-pencil" aria-hidden="true" style=" color:black; font-size:15px;"></i></a>&nbsp;
                      <a href="#" id="${obj.salary_id }" class="delete"><i class="fa fa-trash-o" aria-hidden="true" style="color:black; font-size:15px;"></i></a>&nbsp;&nbsp;
                      <a href="pdf\invoice-db.php?id=${obj.salary_id }" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red; font-size:15px;"></i></a>
                  </td>
                </tr>`;
            
            return row;
        }

        function add_salary() {
            var admin_id = $("#admin_id").val();
            var month = $("#sal_month").val();
            var year = $("#sal_year").val();
            $.ajax({
                beforeSend: function () {
                    $('.ajax-loader').css("visibility", "visible");
                },
                url: 'esalfatch.php',
                type: 'POST',
                dataType: "html",
                data: {admin_id: admin_id, month: month, year: year},
                complete: function () {
                    $('.ajax-loader').css("visibility", "hidden");
                },
                success: function (data) {
                    swal(data.trim(),'','success');
                    $('#add_salary').modal('hide');
                    year_change();
                }
            });
        }

       //Data Table
        $(document).ready(function () {
            month_change();

        });

        function datatable() {
            var table = $('#salary').DataTable({
                scrollY: '400px',
                scrollX: 'auto',
                ordering: false,
                scroller: false,
                pageLength: 100,
                fixedColumns: true,
            });
        }
        // edit Salary
        $(document).on('click', '.update', function () {
            var s_id = $(this).attr("id");
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {s_id: s_id, action: 'salary_view'},
                datatype: "json",
                success: function (data) {
                    $("#view_field").html(data);
                }
            });
        });

        // Delete Salary
        $(document).on('click', '.delete', function () {
            var s_id = $(this).attr("id");
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
                            type: 'post',
                            data: {s_id: s_id, action: 'delete_salary'},
                            beforeSend: function () {
                                $("#id").show();
                            },
                            success: function (data) {
                                swal("Salary Delete successfully",'','success');
                                month_change();
                            }
                        });
                    }else {
                        swal("Your record is safe!",'','info');
                    }
                });
        });
        // Edit Salary
        function update_salary() {
            var select_staff = document.getElementById('employee').value;
            var basic = document.getElementById('ubasic').value;
            var working_days = document.getElementById('working_days').value;
            var present_days = document.getElementById('present_days').value;
            var absent_days = document.getElementById('absent_days').value;
            var da = document.getElementById('uda').value;
            var hra = document.getElementById('uhra').value;
            var conveyance = document.getElementById('uconveyance').value;
            var allow = document.getElementById('uallow').value;
            var m_allow = document.getElementById('um_allow').value;
            var tds = document.getElementById('utds').value;
            var esi = document.getElementById('uesi').value;
            var pf = document.getElementById('upf').value;
            var e_leave = document.getElementById('ue_leave').value;
            var over_time = document.getElementById('over_time').value;
            var incentive = document.getElementById('incentive').value;
            var break_violation = document.getElementById('break_violation').value;
            var late_fine = document.getElementById('late_fine').value;
            var proftax = document.getElementById('uproftax').value;
            var l_wel = document.getElementById('ul_wel').value;
            var s_id = document.getElementById('s_id').value;
            var empId = document.getElementById('empId').value;
            $.ajax({
                url: "update.php",
                type: "POST",
                data: {
                    emp_name: select_staff,
                    basic: basic,
                    working_days: working_days,
                    present_days: present_days,
                    absent_days: absent_days,
                    da: da,
                    hra: hra,
                    conveyance: conveyance,
                    allow: allow,
                    m_allow: m_allow,
                    tds: tds,
                    esi: esi,
                    pf: pf,
                    eId:empId,
                    e_leave: e_leave,
                    over_time: over_time,
                    incentive: incentive,
                    break_violation: break_violation,
                    late_fine: late_fine,
                    proftax: proftax,
                    l_wel: l_wel,
                    s_id: s_id,
                    action: 'update_salary'
                },
                datatype: "html",
                success: function (data) {
                    let res = JSON.parse(data);
                    swal(res.message,'','success');
                    $("#edit_salary").modal('hide');
                    makeDecision('salary-body', res.data);
                }
            });
        }
    </script>
    <?php
} else {
    header("Location:index.php");
}
?>