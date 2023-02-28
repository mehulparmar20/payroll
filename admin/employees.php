<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Employee';
    include 'admin_header.php';
    ?>
    
    <style>
        .modal-content1 {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 0.3rem;
    outline: 0;
}
    </style>
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
            <?php  if($device_status != 'offline' && $emp_remaining > 0){ ?>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_employee"><i
                            class="fa fa-plus"></i> Add Employee</a>
            </div>
            <!--<div class="col-auto float-right ml-auto">-->
            <!--    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addexcelemployee"><i-->
            <!--                class="fa fa-plus"></i> Import Employee Excel</a>-->
            <!--</div>-->
            <?php }else{ ?>
                <div class="col-auto float-right ml-auto" id="employee-add">
                    <?php if($emp_remaining > 0){ ?>
                    <a href="#" class="btn" style="background-color: #e9ecef; border: 1px solid #e9ecef;" id="devicestatusInfo"><i
                                class="fa fa-plus"></i> Add Employee</a>
                        <?php } else{ ?>
                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" id="buy_cards-btn"><i
                        class="fa fa-plus-circle"></i> Buy Employee card</a>
                             <?php  } ?>
                </div>
                <!--<div class="col-auto float-right ml-auto">-->
                <!--    <a href="#" class="btn" style="background-color: #e9ecef; border: 1px solid #e9ecef;" id="devicestatusInfo"><i-->
                <!--                class="fa fa-plus"></i> Import Employee Excel</a>-->
                <!--</div>-->
            <?php } ?>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Total Employee</b></span>
                <h3 style="color: black" id="employee-total-counter"></h3>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Remaining Employee</b></span>
                <h3 style="color: black" id="employee-remaining-counter"></h3>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Total new employee</b></span>
                <h3 style="color: black" id="new-joining"></h3>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Total left employee</b></span>
                <h3 style="color: black" id="left-total"></h3>
            </div>
        </div>
        </div>
    <!-- /Page Header -->
    <div class="row filter-row">
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select name="searchcolumn" id="searchcolumn" class="custom-select" >
                    <option value="e_firstname" selected>Name</option>
                    <option value="e_email">Email</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group">
                <input type="text" name="search_box" id="search_box" class="form-control"
                       placeholder="Search Here (Enter Name of Email)"/>
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="load_data(1)" class="custom-select" id="select_row" name="select_row">
                    <option value="10" >Show Entries</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="load_data(1)" class="custom-select" id="column" name="column">
                    <option value="e_id" selected>Sorting Column</option>
                    <option value="join_date">Joining Date</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <div class="form-group">
                <select onchange="load_data(1)" class="custom-select" id="sorting" name="sorting">
                    <option value="ASC" selected>Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" id="dynamic_content">


            </div>
        </div>
    </div>
    </div>
    <!-- /Page Wrapper -->
    <style>

    </style>

    <?php include 'footer.php'; ?>

    <div id="view_document" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="view_docs" class="row row-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .modal {
            overflow-y: auto;
        }
    </style>
    <div id="add_employee" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="employee-container" style="z-index: 1600"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class=" text-danger"
                                                                               style="font-size:20px">*</span></label>
                                <input class="form-control" required id="f_name" name="f_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name<span class=" text-danger"
                                                                             style="font-size:20px">*</span> </label>
                                <input class="form-control" required id="l_name" type="text">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class=" text-danger"
                                                                          style="font-size:20px">*<span
                                                class=" text-dark" style="font-size:15px">     (Password Sent in the Email)</span></span></label>
                                <input class="form-control" onchange="val_email(this.value)" required id="email"
                                       type="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender <span class=" text-danger" style="font-size:20px">*</span></label>
                                <select class="form-control" id="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Equipment type<span class=" text-danger"
                                                                                  style="font-size:20px">*</span></label>
                                <div class="add-group-btn">
                                    <select onchange="get_employee_card(this.value)" class="form-control" required
                                            name="e_type" id="e_type">
                                        <option value="">Select Equipment type</option>
                                        <?php
                                        // $sql = mysqli_query($conn, "SELECT * FROM `hardwere_type`");
                                        // while ($row = mysqli_fetch_assoc($sql)) {
                                        //     ?>
                                            <option value="<?php // echo $row['hardware_id'] ?>"><?php // echo $row['hardware_name']; ?></option>
                                             <?php 
                                        // }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Employee ID<span class=" text-danger"
                                                                               style="font-size:20px">*</span></label>
                                <div class="add-group-btn" id="get_employee_card">
                                    <select class="form-control" required name="emp_id" id="emp_id">
                                        <option value="">Select Employee Card Number</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Joining Date <span class=" text-danger"
                                                                                 style="font-size:20px">*</span></label>
                                <div><input class="form-control" required id="join_date" type="date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone<span class=" text-danger"
                                                                         style="font-size:20px">*</span></label>
                                <input class="form-control" required
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');"
                                       id="ph_no" name="pn_no" type="text">
                                <input class="form-control" id="page" value="<?php echo $_GET['page']; ?>"
                                       type="hidden">

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Department <span class=" text-danger" style="font-size:20px">*</span></label>
                                <div class="add-group-btn" id="view_department">
                                    <select class="form-control" required id="department">
                                        <option value="">Select Department</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['departments_id']; ?>"><?php echo $row['departments_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Add</label>
                                <button type="button" style="margin-top: 7px" class="btn btn-primary"
                                        id="department-add"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Designation <span class=" text-danger" style="font-size:20px">*</span></label>
                                <div class="add-group-btn" id="designation_data">
                                    <select class="form-control" required id="designation">
                                        <option value="">Select Department</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM designation where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['designation_id']; ?>"><?php echo $row['designation_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Add</label><br>
                                <button type="button" style="margin-top: 7px" class="btn btn-primary"
                                        id="designation-add"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Employee Salary<span class=" text-danger"
                                                                                   style="font-size:20px">*</span></label>
                                <input class="form-control" id="salary"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');" name="salary"
                                       type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Shift <span class=" text-danger" style="font-size:20px">*</span></label>
                                <div class="add-group-btn">
                                    <select class="form-control" required id="shift_no">
                                        <option value="">Select Shift</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM company_time where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['time_id']; ?>"><?php echo $row['shift_no'] . " ( " . $row['company_in_time'] . " To " . $row['company_out_time'] . " )"; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <input type="submit" class="btn btn-primary submit-btn" id="insert" onclick="addemp()"
                               name="insert">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Data -->

    <div id="edit_employee" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="sauth">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class=" text-danger"
                                                                               style="font-size:20px">*</span></label>
                                <input class="form-control" required id="uf_name" name="uf_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name<span class=" text-danger"
                                                                             style="font-size:20px">*</span>
                                </label>
                                <input class="form-control" required id="ul_name" type="text">
                                <input class="form-control" required id="ue_id" type="hidden">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender <span class=" text-danger" style="font-size:20px">*</span></label>
                                <select class="form-control" id="ugender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class=" text-danger"
                                                                          style="font-size:20px">*<span
                                                class=" text-dark" style="font-size:15px">     (Password Sent in the Email)</span></span></label>
                                <input class="form-control" required id="uemail" type="email">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Joining Date <span class=" text-danger"
                                                                                 style="font-size:20px">*</span></label>
                                <div><input class="form-control" id="ujoin_date" type="date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone<span class=" text-danger"
                                                                         style="font-size:20px">*</span></label>
                                <input class="form-control"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');"
                                       id="uph_no" name="upn_no" type="text">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Department  <span class=" text-danger" style="font-size:20px">*</span></label>
                                <select class="form-control" id="udepartment">
                                    <option>Select Department</option>
                                    <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                        <option value="<?php echo $row['departments_id']; ?>"><?php echo $row['departments_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Add</label>
                                <button type="button" style="margin-top: 7px" class="btn btn-primary"
                                        id="department-add"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Designation <span class=" text-danger"
                                                         style="font-size:20px">*</span></label>
                                <div class="add-group-btn">
                                    <select class="form-control" required id="udesignation">
                                        <option>Select Department</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM designation where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['designation_id']; ?>"><?php echo $row['designation_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Add</label>
                                <button type="button" style="margin-top: 7px" class="btn btn-primary"
                                        data-toggle="modal" data-target="#add_department"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="main_edit_salary">
                                <!--<label class="col-form-label">Employee Salary<span class=" text-danger"-->
                                                                                   <!--style="font-size:20px">*</span></label>-->
                                <!--<input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '');"-->
                                <!--       required id="usalary" name="salary" type="text">-->
                                <!--<div class="input-group mb-3" id="salary_view_manage">-->
                                <!--  <input type="text" class="form-control" placeholder="Enter Password to see salary" id="get_pass_view_salary">-->
                                <!--  <div class="input-group-append" id="view_salary_auth" data-id="">-->
                                <!--    <span class="input-group-text" id="basic-addon2">View Salary</span>-->
                                <!--  </div>-->
                                <!--</div>-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Shift <span class=" text-danger" style="font-size:20px">*</span></label>
                                <div class="add-group-btn" id="shift_no">
                                    <select class="form-control" required id="ushift_no">
                                        <option value="">Select Shift</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM company_time where admin_id = " . $_SESSION['admin_id'] . " ");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <option value="<?php echo $row['time_id']; ?>"><?php echo $row['shift_no'] . " ( " . $row['company_in_time'] . " To " . $row['company_out_time'] . " )"; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="submit-section">
                        <input type="submit" class="btn btn-primary submit-btn" value="Update" id="uinsert"
                               onclick="editemp()" name="uinsert">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-content modal-content1" style="display:none">
                        <div class="modal-header">
                            <h5 class="modal-title">Enter The Accounting Passowrd</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input class="form-control" name="password" id="password" type="password">
                            </div>
                            <!--<div class="text-right">-->
                                <!--<a href="password_change.php">Click here to Reset Password</a>-->
                            <!--</div>-->
                            <div style="padding-top: 8px" class="text-center">
                                <span class="text-danger" id="error-message">Invalid Enter Password</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                            <button type="submit" onclick="salary_auth()" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
    </div>
    </div>

    <!-- QR Code Generate Modal -->
    <div id="qr_code" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code Generate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="chartdata.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Employee <span class="text-danger">*</span></label>
                                    <select id="employee_id" class="form-control" multiple name="employee_id[]">
                                        <option value="" disabled>Select Employee</option>
                                        <?php
                                        //show employees on add salary form from employees table
                                        $query = mysqli_query($conn, "select * from employee where admin_id = " . $_SESSION['admin_id'] . " and employee_status = 1 ORDER BY e_id DESC");
                                        while ($selectstaff = mysqli_fetch_array($query)) {
                                            echo "<option value='" . $selectstaff['emp_cardid'] . "'>" . $selectstaff['e_firstname'] . " " . $selectstaff['e_lastname'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary submit-btn"> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /QR Code Generate Modal  -->
    <div class="modal fade bs-example-modal-md" id="addexcelemployee" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="myMediamModalLabel" aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="topic-title">Import Employee Excel</h4>
                    <button type="button" class="close" id="closeexcelimport">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">
                                <form id="student-excel" method="post" autocomplete="off">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="studentexcel" class="col-form-label">Select File (.xlsx
                                                only)</label>
                                            <input type="file" id="upempexcel" class="form-control"
                                                   accept=".csv, .xlsx" value="Upload Excel"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="student_format.xlsx" class="btn btn-danger btn-anim">Excel format</a>
                    <button type="button" class="btn btn-secondary" id="closeexcelimport">Close</button>
                    <div class="spinner-border text-purple m-2" role="status" id="loader" style="display: none">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="submit" onclick="uploademployeeExcel()"
                            class="btn btn-purple waves-effect waves-light">Add
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Add Fingerprint Modal -->
    <div id="updateUserPassword" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Update Password <span class="text-danger">*</span></label>
                                <input type="text" name="userpassword" class="form-control" id="userpassword">
                                <input type="hidden" name="userpassid" class="form-control" id="userpassid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="chnageUserPassword" class="btn btn-primary">Update password</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Fingerprint Modal  -->

    <!-- Modal load container -->
    <div class="employee-modal-container"></div>
    <div class="plan-container"></div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alertify.js/0.3.1/alertify.min.js" integrity="sha512-iLGyc5M+hda8d6KPvgugjXb8AeT56tKjFt6mgKCzu20n7NMC49sJ0CY+oLnsP2zY0AQFe0OXdSH36vT8Qbmtsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let currentemail = '';
        //For Add New Employee
        async function addemp() {
            var f_name = document.getElementById('f_name').value;
            var l_name = document.getElementById('l_name').value;
            var email = document.getElementById('email').value;
            // var emp_id = document.getElementById('emp_id').value;
            var join_date = document.getElementById('join_date').value;
            var phone_no = document.getElementById('ph_no').value;
            var gender = document.getElementById('gender').value;
            var admin_id = document.getElementById('admin_id').value;
            var department = document.getElementById('department').value;
            var designation = document.getElementById('designation').value;
            var salary = document.getElementById('salary').value;
            var shift_no = document.getElementById('shift_no').value;
            if (val_f_name(f_name)) {
                if (val_l_name(l_name)) {
                    if (val_email(email)) {
                        let resdata = await emailexist(email);
                        const status = JSON.parse(resdata).status;
                        if(status === "new"){
                            if (val_join_date(join_date)) {
                                if (val_phone_no(phone_no)) {
                                    if (val_gender(gender)) {
                                        if (val_department(department)) {
                                            if (val_designation(designation)) {
                                                if (val_salary(salary)) {
                                                    if (val_shift(shift_no)) {
                                                        $(".wrap-loader").show();
                                                        $.ajax({
                                                            url: "insert.php",
                                                            type: "POST",
                                                            data: {
                                                                f_name: f_name,
                                                                l_name: l_name,
                                                                email: email,
                                                                gender: gender,
                                                                // emp_id: emp_id,
                                                                join_date: join_date,
                                                                ph_no: phone_no,
                                                                admin_id: admin_id,
                                                                department: department,
                                                                designation: designation,
                                                                e_salary: salary,
                                                                shift_no: shift_no,
                                                                action: 'addemployee'
                                                            },
                                                            dataType: 'html',
                                                            success: function (data) {
                                                                // console.log(data)
                                                                var res = JSON.parse(data);
                                                                // console.log(res);
                                                                if(res.status == 'success'){
                                                                    swal({
                                                                          title: res.status,
                                                                          text: res.message,
                                                                          icon: 'app/img/success.gif'
                                                                        });
                                                                        $(".swal-icon").html('<img src="app/img/success.gif" height="100px">');
                                                                        emptyform('add');
                                                                }else if(res.status == 'offline'){
                                                                    swal({
                                                                          title: "Device is appearing offline!",
                                                                          text: "Your Device is offline.Make sure the device has been connected to the Internet or check device cable or restart device.",
                                                                          icon: 'app/img/no-signal.png'
                                                                        });
                                                                        $(".swal-icon").html('<img src="app/img/no-signal.png" height="100px">');
                                                                        emptyform('add');
                                                                }else if (res.status == 'failed'){
                                                                    swal({
                                                                          title: res.status,
                                                                          text: res.message,
                                                                          icon: 'app/img/no-signal.png'
                                                                        });
                                                                        $(".swal-icon").html('<img src="app/img/giphy.gif" height="100px">');
                                                                        emptyform('add');
                                                                }
                                                                $(".wrap-loader").hide();
                                                                $("#add_employee").modal('hide');
                                                                load_data(1);
                                                            }
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }else{
                            swal("Email already exists","","info");
                        }
                    }
                }
            }

        }

        function val_f_name(val) {
            if (val == '') {
                swal('Enter employee first name','','info');
                return false;
            } else {
                return true;
            }
        }
        
        function emptyform(from){
            if(from == 'add'){
                document.getElementById('f_name').value = '';
                document.getElementById('l_name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('join_date').value = '';
                document.getElementById('ph_no').value = '';
                document.getElementById('gender').value = '';
                document.getElementById('department').value = '';
                document.getElementById('designation').value = '';
                document.getElementById('salary').value = '';
            }else{
                $("#uf_name").val('');
                $("#ul_name").val('');
                $("#ue_id").val('');
                $("#uemail").val('');
                $("#ugender").val('');
                $("#ujoin_date").val('');
                $("#uph_no").val('');
                $("#udepartment").val('');
                $("#udesignation").val('');
                $("#usalary").val('');
                $("#ushift_no").val('');
            }
        }

        function val_l_name(val) {
            if (val == '') {
                swal('Enter employee last name','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_email(val) {

            if (val == '') {
                swal('Enter employee email','','info');
                return false;
            } else if (val.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
                swal('Enter valid email','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_shift(val) {
            if (val == '') {
                swal('Select employee company shift','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_join_date(val) {
            if (val == '') {
                swal('Select employee joining date','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_phone_no(val) {
            if (val == '') {
                swal('Enter employee contact number','','info');
                return false;
            } else if (val.length < 10 || val.length >= 11) {
                swal('Enter only 10 digits contact number','','info');
            } else {
                return true;
            }
        }

        function val_gender(val) {
            if (val == '') {
                swal('Select employee gender','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_department(val) {
            if (val == '') {
                swal('Select employee department','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_designation(val) {
            if (val == '') {
                swal('Select employee designation','','info');
                return false;
            } else {
                return true;
            }
        }

        function val_salary(val) {
            if (val == '') {
                swal('Enter employee basic salary','','info');
                return false;
            } else {
                return true;
            }
        }

        function add_department() {
            var department_name = $('#departments_name').val();
            var admin_id = $('#admin_id').val();
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: {department_name: department_name, admin_id: admin_id, action: 'department_add'},
                dataType: "html",
                success: function (data) {
                    $("#view_department").html(data);
                    $("#add_department").modal('hide');
                    department_modal = 0;
                }
            });
        }

        function val_qr_Code(val) {
            if (val == '') {
                swal('Select Employee', '','info');
                return false;
            } else {
                return true;
            }
        }

        // view employee document data
        $(document).on('click', '.view_doc', function () {
            var e_id = $(this).attr("id");
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {e_id: e_id, action: 'view_doc'},
                dataType: "html",
                success: function (data) {
                    $('#view_docs').html(data);
                }
            });
        });
        
        // view_salary_auth
         $(document).on('click', '#view_salary_auth', function () {
           let pass = $("#get_pass_view_salary").val();
           let eid= $('#view_salary_auth').data('id');
           console.log(pass, eid);
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {e_id: eid, pass: pass ,action: 'view_edit_salary'},
                dataType: "html",
                success: function (data) {
                    let res = JSON.parse(data);
                   console.log("response:> ", res);
                   if(res[0]){
                       let sal = $("#sauth").val();
 



                       $("#main_edit_salary").html(`<label class="col-form-label">Employee Salary<span class=" text-danger" style="font-size:20px">*</span></label><input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required id="usalary" name="salary" type="text">`);
                        $("#usalary").val(sal);      
                   } else {
                       alert("Your password is incorrect!");
                       alertify.error('Your password is incorrect!');
                   }
                }
            });
        });
        
        
        // view edit data
        $(document).on('click', '.edit', function () {
            var employee_id = $(this).attr("id");
            makeEditModalClear();
            $(".wrap-loader").show();
            $.ajax({
                url: "read.php",
                method: "POST",
                data: {e_id: employee_id, action: 'e_view'},
                dataType: "html",
                success: function (data) {
                    var data = JSON.parse(data);
                    console.log("data:>", data)
// data.e_salary
                    $("#uf_name").val(data.e_firstname);
                    $("#ul_name").val(data.e_lastname);
                    $("#ue_id").val(data.emp_cardid);
                    currentemail = data.e_email;
                    $("#uemail").val(data.e_email);
                    $("#ugender").val(data.e_gender);
                    $("#ujoin_date").val(data.join_date);
                    $("#uph_no").val(data.e_phoneno);
                    $("#udepartment").val(data.department);
                    $("#udesignation").val(data.designation);
                    // $("#usalary").val("*****");
                    // $("#usalary").val(data.e_salary);
                    $("#sauth").val(data.e_salary);
                    $("#ushift_no").val(data.shift_no);
                    $("#uinsert").val("Update");
                    $(".wrap-loader").hide();
                    
                     $("#main_edit_salary").html(`<label class="col-form-label">Employee Salary<span class=" text-danger" style="font-size:20px">*</span></label><div class="input-group mb-3" id="salary_view_manage">
                                                      <input type="text" class="form-control" placeholder="Enter password to see salary" id="get_pass_view_salary">
                                                      <div class="input-group-append" style="cursor: pointer;" id="view_salary_auth" data-id="">
                                                        <span class="input-group-text" id="basic-addon2"><i class="fa fa-eye" aria-hidden="true"></i>View Salary</span>
                                                      </div>
                                                    </div>`);
                                                    
                    $('#view_salary_auth').data('id', data.e_id);
                }
            });
        });
        
        function makeEditModalClear (){
                    $("#uf_name").val("");
                    $("#ul_name").val("");
                    $("#ue_id").val("");
                    $("#uemail").val("");
                    $("#ugender").val("");
                    $("#ujoin_date").val("");
                    $("#uph_no").val("");
                    $("#udepartment").val("");
                    $("#udesignation").val("");
                    $("#usalary").val("");
                    $("#sauth").val("");
                    $("#ushift_no").val("");
        }

       async function load_data(page, query = '') {
            var admin_id = $("#admin_id").val();
            var select_row = $("#select_row").val();
            var sorting = $("#sorting").val();
            var column = $("#column").val();
            var searchcolumn = $("#searchcolumn").val();
            $(".wrap-loader").show();
            $.ajax({
                url: "getpagedata.php",
                method: "POST",
                data: {
                    page: page,
                    select_row: select_row,
                    sorting: sorting,
                    column: column,
                    admin_id: admin_id,
                    query: query,
                    searchcolumn : searchcolumn,
                },
                success: function (data) {
                    var res = JSON.parse(data);
                    processusertable(res.table,res.paginate,res.page,res.totallink,res.no)
                    $(".wrap-loader").hide();
                    employeereaming();
                }
            });
        }

        var timeOut = 0;
        $(document).on('keyup', '#search_box', function (e) {
            console.log("called 1")
            // clearTimeout(timeOut);
            // var query = $('#search_box').val();
            // timeOut = setTimeout(() => {
            //     console.log("called 2")
            //     if(query != ""){
            //         load_data(1, query);
            //     }
            // }, 800);
        });
        

        $(document).ready(function () {
            load_data(1);
            $(document).on('click', '.page-link', function () {
                var page = $(this).data('page_number');
                var query = $('#search_box').val();
                load_data(page, query);
            });
            
            setInterval(function () {

                $('#hiddiv').load('timeout.php');
            }, 100000);
        });

        // view edit employee
        async function editemp() {
            var f_name = document.getElementById('uf_name').value;
            var l_name = document.getElementById('ul_name').value;
            var email = document.getElementById('uemail').value;
            var gender = document.getElementById('ugender').value;
            var e_id = document.getElementById('ue_id').value;
            var join_date = document.getElementById('ujoin_date').value;
            var phone_no = document.getElementById('uph_no').value;
            var department = document.getElementById('udepartment').value;
            var designation = document.getElementById('udesignation').value;
            var salary = 0;
            if($("#usalary").val()) { 
                salary = $("#usalary").val();
            } else {
                salary =  $("#sauth").val();
            }
            var shift_no = document.getElementById('ushift_no').value;
            if (val_f_name(f_name)) {
                if (val_l_name(l_name)) {
                    if (val_email(email)) {
                        let resdata = await emailexist(email,currentemail,true);
                        const status = JSON.parse(resdata).status;
                        if(status === 'new'){
                            if (val_join_date(join_date)) {
                                if (val_phone_no(phone_no)) {
                                    if (val_department(department)) {
                                        if (val_designation(designation)) {
                                            if (val_salary(salary)) {
                                                if (val_shift(shift_no)) {
                                                    $(".wrap-loader").show();
                                                    $.ajax({
                                                        url: "update.php",
                                                        type: "POST",
                                                        data: {
                                                            f_name: f_name,
                                                            l_name: l_name,
                                                            email: email,
                                                            gender: gender,
                                                            e_id: e_id,
                                                            join_date: join_date,
                                                            ph_no: phone_no,
                                                            department: department,
                                                            designation: designation,
                                                            salary: salary,
                                                            shift_no: shift_no,
                                                            action: 'edit_employee'
                                                        },
                                                        success: function (data) {
                                                            $("#edit_employee").modal('hide');
                                                            swal({
                                                                text: "Employee Edit Successfully.",
                                                                timer: 3000,
                                                            });
                                                            load_data(1);
                                                        }
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        function add_des() {
            var designation_name = $('#designation_name').val();
            var department = $('#department').val();
            var admin_id = $('#admin_id').val();
            $(".wrap-loader").show();
            $.ajax({
                url: "insert.php",
                type: "POST",
                data: {
                    designation_name: designation_name,
                    department: department,
                    admin_id: admin_id,
                    action: 'add_designation'
                },
                datatype: "html",
                success: function (data) {
                    $('#designation_data').html(data);
                    $('#add_designation').modal('hide');
                    designation_modal = 0;
                    $(".wrap-loader").hide();
                }
            });
        }

        var department_modal = 0;
        var designation_modal = 0;
        // fetch department Modal
        $(document).on('click', '#department-add', function (e) {
            e.preventDefault();
                $('.employee-container').load('department_modal.php', function (result) {
                    $('#add_department').modal({show: true});
                });
        });

        $(document).on('click', '#deactivate-user', function (e) {
            e.preventDefault();
            const e_id = this.name;
            $('.employee-modal-container').load('deactivate_modal.php', function (result) {
                $('#deactivate-modal').modal({show: true});
                $("#employee-id").val(e_id);
            });
        });

        $(document).on('click', '.modalDepartment', function () {
            $('#add_department').modal('hide');
            $('#employee-container').empty();
            department_modal = 0;
        });

        function updateUserPassword(id) {
            $("#userpassid").val(id);
            $('#updateUserPassword').modal('show');
        }
        
        
        // fetch designation Modal
        $(document).on('click', '#designation-add', function (e) {
            e.preventDefault();
            $(".wrap-loader").show();
            $('.employee-container').load('designation_modal.php', function (result) {
                $('#add_designation').modal({show: true});
                $(".wrap-loader").hide();
            });
        });
    
          // fetch designation Modal
        $(document).on('click', '#chnageUserPassword', function () {
            var id = $("#userpassid").val();
            var password = $("#userpassword").val();
            $.ajax({
                    url: 'update.php',
                    type: 'post',
                    data: {empId: id,password: password, action: 'updateEmployeePassword'},
                    success: function (data) {
                        $("#userpassid").val('');
                        $("#userpassword").val('');
                        $('#updateUserPassword').modal('hide');
                        swal("Password Update successfully");
                    }
                });
        });


        $(document).on('click', '.modalDesignation', function () {
            $('#add_designation').modal('hide');
            $('.employee-container').empty();
            designation_modal = 0;
        });
        // view delete
        // function for delete data
        $(document).on('click', '#deactivate-employee', function () {
            var id = $("#employee-id").val();
            var resigndate = $("#resign-date").val();
            swal({
                title: "Are you sure?",
                text: "Once an employee is deactivated you can not activate it again!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                    if (willDelete) {
                        $(".wrap-loader").show();
                        $.ajax({
                            url: 'action.php',
                            type: 'post',
                            data: {e_id: id, resigndate:resigndate,action: 'deactive'},
                            success: function (data) {
                                console.log(data);
                                var res = JSON.parse(data);
                                if(res.status == 'success'){
                                    swal(res.message,'','success');
                                    load_data(1);
                                }else{
                                    swal(res.message,'','info');
                                }
                                $(".wrap-loader").hide();
                            }
                        });
                    } else {
                        swal("Your data is safe!",'','info');
                    }
                });
        });
    $(document).on('click','#devicestatusInfo',function(){
       swal({
              title: "Device is appearing offline!",
              text: "Your Device is offline.Make sure the device has been connected to the Internet or check device cable or restart device.",
              icon: 'app/img/no-signal.png'
            }); 
            $(".swal-icon").html('<img src="app/img/no-signal.png" height="100px">');
        });
    </script>
    <?php
} else {
    header("Location:../index.php");
}