<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Experience';
    include'admin_header.php';
    ?>					
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title"><?php echo $page; ?></h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo $page; ?></li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_experience"><i class="fa fa-plus-circle"></i> Add experience</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div id="show_detail">

                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
   
    <?php  include 'footer.php';  ?>
<!-- Add Termination Modal -->
<div id="add_experience" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Add Experience</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Manager Name <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="manager_name" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Manager Designation <span class="text-danger">*</span></label>
                            <input type="text" name="emp_id" id="manager_designation" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Employee <span class="text-danger">*</span></label>
                            <select class="form-control" id="e_id" onchange="joining_date()" name="e_id">
                                <option value="error">--- select employee ---</option>
                                <?php
                                //show employees on add salary form from employees table
                                $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname'] ?></option>
                                <?php } ?>
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" disabled value="<?php echo $company_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Period From <span class="text-danger">*</span></label>
                            <div>
                                <input type="date" name="period_from" id="period_from" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Period To <span class="text-danger">*</span></label>
                            <div>
                                <input type="date" name="period_to" id="period_to" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Application Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input type="date" disabled name="application_date" id="application_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="submit-section" style="margin-left: 37% " >
                        <input type="submit" class="btn btn-primary submit-btn" onclick="resignation()" id="insert" name="insert">	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Termination Modal -->

<!-- edit Termination Modal -->
<div id="edit_experience" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Edit Experience</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit_form" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Name <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="emanager_name" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Manager Designation <span class="text-danger">*</span></label>
                                <input type="text" name="emp_id" id="emanager_designation" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Employee <span class="text-danger">*</span></label>
                                <select class="form-control" id="ee_id" onchange="joining_date_2()" name="e_id">
                                    <option value="error">--- select employee ---</option>
                                    <?php
                                    //show employees on add salary form from employees table
                                    $query = mysqli_query($conn, "select e_id,e_firstname,e_lastname from employee where admin_id = '" . $_SESSION['admin_id'] . "' ");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <option value="<?php echo $row['e_id'] ?>"><?php echo $row['e_firstname'] . '&nbsp;' . $row['e_lastname'] ?></option>
                                    <?php } ?>
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Period From <span class="text-danger">*</span></label>
                                <div>
                                    <input type="date" name="period_from" id="eperiod_from" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Period To <span class="text-danger">*</span></label>
                                <div>
                                    <input type="date" name="period_to" id="eperiod_to" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Application Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="date" disabled name="application_date" id="eapplication_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                    <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                                    <input type="hidden" name="experience_id" id="eexperience_id" >
                                </div>
                            </div>
                        </div>
                        <div class="submit-section" style="margin-left: 37% " >
                            <input type="submit" class="btn btn-primary submit-btn" value="Save" id="insert" name="insert">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Termination Modal -->

<script>
    function joining_date()
    {
        var e_id = $('#e_id').val();
        $.ajax({
            url: '../control.php',
            method: 'POST',
            data: {e_id: e_id, action: 'fetch_joining_date'},
            dataType: 'html',
            success: function (data)
            {
                var j = JSON.parse(data);
                $('#period_from').val(j.join_date);
            }
        });
    }

    function joining_date_2()
    {
        var e_id = $('#ee_id').val();
        $.ajax({
            url: '../control.php',
            method: 'POST',
            data: {e_id: e_id, action: 'fetch_joining_date'},
            dataType: 'html',
            success: function (data)
            {
                var j = JSON.parse(data);
                $('#eperiod_from').val(j.join_date);
            }
        });
    }

    $(document).ready(function () {
        view_data();
        
        });
        
        function view_data()
        {
            $.ajax({
                url: '../control.php',
                type: 'POST',
                data: {action: 'show_experience'},
                success: function (data) {
                    $('#show_detail').html(data);
                    datatable();
                }
            });
        }
        
        // fetch edit data
        $(document).on('click', '.edit_data', function () {
            var experience_id = $(this).attr("id");

            $.ajax({
                url: "../control.php",
                method: "POST",
                data: {experience_id: experience_id, action: 'fetch_experience'},
                dataType: "json",
                success: function (data) {
                    $('#ecompany_name').val(data.company_name);
                    $("#ee_id").val(data.e_id);
                    $("#eperiod_from").val(data.period_from);
                    $("#eperiod_to").val(data.period_to);
                    $("#eapplication_date").val(data.application_date);
                    $("#eexperience_id").val(data.experience_id);
                    $("#emanager_name").val(data.manager_name);
                    $("#emanager_designation").val(data.manager_designation);
                    $("#edit_experience").modal('show');
                }
            });
        });

        // edit function
        $('#edit_form').on('submit', function (event) {
            event.preventDefault();
            var e_id = $('#ee_id').val();
            var company_name = $('#ecompany_name').val();
            var period_from = $('#eperiod_from').val();
            var period_to = $('#eperiod_to').val();
            var application_date = $('#eapplication_date').val();
            var admin_id = $('#admin_id').val();
            var experience_id = $('#eexperience_id').val();
            var manager_name = $('#emanager_name').val();
            var manager_designation = $('#emanager_designation').val();
            var month = (new Date(period_to).getMonth() - new Date(period_from).getMonth() + (12 * (new Date(period_to).getFullYear() - new Date(period_from).getFullYear())));
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_company_name(company_name)) {
                            if (val_period_from(period_from)) {
                                if (val_period_to(period_to)) {
                                    if (val_application_date(application_date)) {
                                        $.ajax({
                                            url: '../control.php',
                                            type: 'post',
                                            data: {e_id: e_id,
                                                company_name: company_name,
                                                period_from: period_from,
                                                period_to: period_to,
                                                application_date: application_date,
                                                admin_id: admin_id,
                                                month: month,
                                                manager_name:manager_name,
                                                manager_designation:manager_designation,
                                                experience_id: experience_id,
                                                action: 'edit_experience'},
                                            success: function (data) {
                                                swal("Edit Successfully.");
                                                $('#edit_experience').modal('hide');
                                                view_data();
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

            function val_manager_name(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Designation');
                    return false;
                } else {
                    return true;
                }
            }
            
            function val_e_id(val)
            {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }

            function val_company_name(val)
            {
                if (val == '') {
                    swal('Please Write Your Company Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_designation(val)
            {
                if (val == '') {
                    swal('Please Write Your Designation');
                    return false;
                } else {
                    return true;
                }
            }

            function val_period_from(val)
            {
                if (val == '') {
                    swal('Please Select Period From Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_period_to(val)
            {
                if (val == '') {
                    swal('Please Select Period to Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_application_date(val)
            {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }
        // Datatable
        function datatable(){
             var table = $('#experience').DataTable({
                // ordering: false,
                paging:   true,
                info : true,
            });
        }
        // insert function
        function resignation() {
            var e_id = $('#e_id').val();
            var company_name = $('#company_name').val();
            var period_from = $('#period_from').val();
            var period_to = $('#period_to').val();
            var application_date = $('#application_date').val();
            var admin_id = $('#admin_id').val();
            var manager_name = $('#manager_name').val();
            var manager_designation = $('#manager_designation').val();
            var month = (new Date(period_to).getMonth() - new Date(period_from).getMonth() + (12 * (new Date(period_to).getFullYear() - new Date(period_from).getFullYear())));
            if (val_manager_name(manager_name)) {
                if (val_manager_designation(manager_designation)) {
                    if (val_e_id(e_id)) {
                        if (val_company_name(company_name)) {
                            if (val_designation(designation)) {
                                if (val_period_from(period_from)) {
                                    if (val_period_to(period_to)) {
                                        if (val_application_date(application_date)) {
                                            $.ajax({
                                                url: '../control.php',
                                                type: 'post',
                                                data: {
                                                    e_id: e_id,
                                                    company_name: company_name,
                                                    period_from: period_from,
                                                    period_to: period_to,
                                                    manager_name: manager_name,
                                                    manager_designation: manager_designation,
                                                    application_date: application_date,
                                                    month: month,
                                                    admin_id: admin_id,
                                                    action: 'add_experience'
                                                },
                                                success: function (data) {
                                                    swal("Experience Latter Added Successfully.")
                                                    $('#add_experience').modal('hide');
                                                    view_data();
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

            function val_manager_name(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_manager_designation(val)
            {
                if (val == '') {
                    swal('Please Write an Manager Designation');
                    return false;
                } else {
                    return true;
                }
            }

            function val_e_id(val)
            {
                if (val == 'error') {
                    swal('Please Select an Employee');
                    return false;
                } else {
                    return true;
                }
            }

            function val_company_name(val)
            {
                if (val == '') {
                    swal('Please Write Your Company Name');
                    return false;
                } else {
                    return true;
                }
            }

            function val_period_from(val)
            {
                if (val == '') {
                    swal('Please Select Period From Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_period_to(val)
            {
                if (val == '') {
                    swal('Please Select Period to Date');
                    return false;
                } else {
                    return true;
                }
            }

            function val_application_date(val)
            {
                if (val == '') {
                    swal('Please Select Application Date');
                    return false;
                } else {
                    return true;
                }
            }

        }

        // delete data
        $(document).on('click', '.delete_data', function () {
            var experience_id = $(this).attr("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this letter!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '../control.php',
                            type: 'POST',
                            data: {id: experience_id,action: 'delete_experience'},
                            success: function (data) {
                                swal("Deleted!", "Your letter has been deleted.", "success");
                                view_data();
                            }
                        });
                    } else {
                        swal("Your letter is safe!");
                    }
                });
    });
</script>

<?php }else{
    header("location:../index.php");
}