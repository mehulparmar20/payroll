<?php
ob_start();
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include 'emp_header.php';
    $sql = mysqli_query($conn, "SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id WHERE employee.e_id = '" . $_SESSION['e_id'] . "' AND employee.admin_id = '" . $_SESSION['admin_id'] . "' ");
    while ($row = mysqli_fetch_array($sql)) {
        ?>
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="card mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap edit-img">
                                    <!--<img class="inline-block" src="assets/img/profiles/avatar-02.jpg" alt="user">-->
                                    <span id="uploaded_image"></span>
                                    <div class="fileupload btn">
                                        <span class="btn-text">edit</span>
                                        <input class="upload" id="file" name="file" type="file">
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0"><?php echo $row['e_firstname'] . ' ' . $row['e_lastname']; ?></h3>
                                                <h6 class="text-muted"><?php echo $row['departments_name']; ?></h6>
                                                <input type="hidden" value="<?php echo $row['departments_id']; ?>"
                                                       id="dp_name">
                                                <div class="staff-id">Employee ID
                                                    : <?php echo $row['emp_cardid']; ?></div>

                                                <div class="small doj text-muted">Date of Join
                                                    : <?php echo date('d-m-Y', $row['join_date']); ?></div>
                                                <div class="staff-id">Email ID : <?php echo $row['e_email']; ?></div>
                                                <div class="staff-id">Phone : +91 <?php echo $row['e_phoneno']; ?></div>
                                            </div>
                                        </div>
                                        <!-- start -->

                                        <div class="col-md-7">
                                            <ul id="view_data2" class="personal-info">
                                            </ul>
                                        </div>
                                        <!-- end -->
                                    </div>
                                </div>

                                <div class="pro-edit">
                                    <a onclick="getData();" class="edit-icon" href="#"><i class="fa fa-pencil"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <!-- Profile Info Tab -->
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Emergency Contact</h3>
                                    <!--<h5 class="section-title">Primary</h5>-->
                                    <ul class="personal-info" id="view_data1">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Bank information</h3>
                                    <ul id="view_data3" class="personal-info">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--<span id="uploaded_image"></span>-->
                    </div>
                </div>
                <!-- /Profile Info Tab -->
            </div>
        </div>
        <!-- /Page Content -->
        <?php
    }
    include 'footer.php';

    ?>
    <!-- Profile Modal -->
    <div id="profile_info" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Birth Date</label>
                                        <div>
                                            <input class="form-control" type="date" id="date_of_birth"
                                                   name="date_of_birth">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="select form-control" id="emp_gender" name="emp_gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" id="emp_address" name="emp_address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pin Code</label>
                                <input type="text" class="form-control" id="pin_code" name="pin_code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" id="alternate_number"
                                       name="alternate_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nationality <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="emp_nationality" name="emp_nationality">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Religion</label>
                                <div>
                                    <input class="form-control" type="text" id="emp_religion" name="emp_religion">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Marital status <span class="text-danger">*</span></label>
                                <select class="select form-control" id="martial_status" name="martial_status">
                                    <option>-</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3 style="padding-left: 38%; ">Emergency Contact</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="person_name" name="person_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Relationship <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="relationship" name="relationship">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="phone_number" name="phone_number">
                                <input type="hidden" id="e_id" name="e_id" value="<?php echo $_SESSION['e_id']; ?>">
                                <input type="hidden" id="admin_id" name="admin_id"
                                       value="<?php echo $_SESSION['admin_id']; ?>">
                                <input type="hidden" id="employee_detail_update" name="employee_detail_update"
                                       value="1">
                                <input type="hidden" id="data_update_status" name="data_update_status" value="1">
                                <input type="hidden" id="eb_update_status" name="eb_update_status" value="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3 style="padding-left: 35%; ">Bank Account Detail</h3>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eb_name" name="eb_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bank Account No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eb_account_number"
                                       name="eb_account_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>IFSC Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eb_ifsc_code" name="eb_ifsc_code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Branch Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eb_branch_name" name="eb_branch_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PAN No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eb_pan_no" name="eb_pan_no">
                            </div>
                        </div>
                        <div class="col-md-6" style="display:none">
                            <div class="form-group">
                                <input type="checkbox" value="1" id="agree">
                                <label>Agree <a href="#" data-target="#view_policy" data-toggle="modal"
                                                onclick="view_policy()">Company Policy</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" id="insert" onclick="addDetails()" name="insert">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Profile Modal -->

    <!-- Policy Modal -->
    <div id="view_policy" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="view_policy" class="row row-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Policy Modal -->
    <script>
        $(document).ready(function () {
            view_profile();
            view_profile_1();
        });

        function view_profile_1() {
            var e_id = $("#e_id").val();
            $.ajax({
                url: 'fetch.php',
                method: 'POST',
                data: {e_id: e_id, action: 'emp_profile'},
                success: function (data) {
                    var get_data = JSON.parse(data);
                    $('#view_data1').html(get_data[0]);
                    $('#view_data2').html(get_data[1]);
                    $('#view_data3').html(get_data[2]);
                }
            });
        }

        //
        // // add other profile data function
        function addDetails() {
            var date_of_birth = $('#date_of_birth').val();
            var emp_address = $('#emp_address').val();
            var pin_code = $('#pin_code').val();
            var state = $('#state').val();
            var emp_gender = $('#emp_gender').val();
            var alternate_number = $('#alternate_number').val();
            var emp_nationality = $('#emp_nationality').val();
            var emp_religion = $('#emp_religion').val();
            var martial_status = $('#martial_status').val();

            var person_name = $('#person_name').val();
            var relationship = $('#relationship').val();
            var phone_number = $('#phone_number').val();

            var eb_name = $('#eb_name').val();
            var eb_account_number = $('#eb_account_number').val();
            var eb_ifsc_code = $('#eb_ifsc_code').val();
            var eb_branch_name = $('#eb_branch_name').val();
            var eb_pan_no = $('#eb_pan_no').val();

            var eb_update_status = $('#eb_update_status').val();
            var employee_detail_update = $('#employee_detail_update').val();
            var data_update_status = $('#data_update_status').val();
            var agree = $('#agree').val();
            var e_id = $('#e_id').val();
            var admin_id = $('#admin_id').val();

            if (val_birth(date_of_birth)) {
                if (val_emp_gender(emp_gender)) {
                    if (val_emp_address(emp_address)) {
                        if (val_state(state)) {
                            if (val_pin_code(pin_code)) {
                                if (val_alternate_number(alternate_number)) {
                                    if (val_emp_nationality(emp_nationality)) {
                                        if (val_emp_religion(emp_religion)) {
                                            if (val_martial_status(martial_status)) {
                                                if (val_person_name(person_name)) {
                                                    if (val_relationship(relationship)) {
                                                        if (val_phone_number(phone_number)) {
                                                            if (val_eb_name(eb_name)) {
                                                                if (val_eb_account_number(eb_account_number)) {
                                                                    if (val_eb_ifsc_code(eb_ifsc_code)) {
                                                                        if (val_eb_pan_no(eb_pan_no)) {
                                                                            if (val_agree(agree)) {
                                                                                $.ajax({
                                                                                    url: "fetch.php",
                                                                                    method: "post",
                                                                                    data: {
                                                                                        date_of_birth: date_of_birth,
                                                                                        emp_address: emp_address,
                                                                                        pin_code: pin_code,
                                                                                        state: state,
                                                                                        emp_gender: emp_gender,
                                                                                        alternate_number: alternate_number,
                                                                                        emp_nationality: emp_nationality,
                                                                                        emp_religion: emp_religion,
                                                                                        martial_status: martial_status,
                                                                                        person_name: person_name,
                                                                                        relationship: relationship,
                                                                                        phone_number: phone_number,
                                                                                        eb_name: eb_name,
                                                                                        eb_account_number: eb_account_number,
                                                                                        eb_ifsc_code: eb_ifsc_code,
                                                                                        eb_branch_name: eb_branch_name,
                                                                                        eb_pan_no: eb_pan_no,
                                                                                        eb_update_status: eb_update_status,
                                                                                        data_update_status: data_update_status,
                                                                                        employee_detail_update: employee_detail_update,
                                                                                        e_id: e_id,
                                                                                        admin_id: admin_id,
                                                                                        action: 'add_profile'
                                                                                    },
                                                                                    success: function (data) {
                                                                                        $('#profile_info').modal('hide');
                                                                                        swal("Profile Setup Successfully");
                                                                                        view_profile_1();
                                                                                        location.reload(true);
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
                                }
                            }
                        }
                    }
                }
            }
        }

        function getData() {
            var e_id = $('#e_id').val();
            $.ajax({
                url: 'fetch.php',
                method: "POST",
                data: {e_id: e_id, action: 'getData'},
                success: function (data) {
                    var data = data.trim();
                    if (data == 'new_user') {
                        $("#profile_info").modal('show');
                    } else {
                        var get = JSON.parse(data);
                        $('#date_of_birth').val(get.profile.date_of_birth);
                        $('#emp_address').val(get.profile.emp_address);
                        $('#pin_code').val(get.profile.pin_code);
                        $('#state').val(get.profile.state);
                        $('#emp_gender').val(get.employee.e_gender);
                        $('#alternate_number').val(get.profile.alternate_number);
                        $('#emp_nationality').val(get.profile.emp_nationality);
                        $('#emp_religion').val(get.profile.emp_religion);
                        $('#martial_status').val(get.profile.martial_status);

                        $('#person_name').val(get.emergency.person_name);
                        $('#relationship').val(get.emergency.relationship);
                        $('#phone_number').val(get.emergency.phone_number);

                        $('#eb_name').val(get.bank.eb_name);
                        $('#eb_account_number').val(get.bank.eb_account_number);
                        $('#eb_ifsc_code').val(get.bank.eb_ifsc_code);
                        $('#eb_branch_name').val(get.bank.eb_branch_name);
                        $('#eb_pan_no').val(get.bank.eb_pan_no);
                        $('#agree').val();
                        $('#e_id').val();
                        $('#admin_id').val();
                        $("#profile_info").modal('show');
                    }
                }
            });
        }

        //
        function val_birth(val) {
            if (val == '') {
                swal({title: 'Please Select Birth Date'});
                return false;
            } else {
                return true;
            }
        }

        function val_emp_gender(val) {
            if (val == '') {
                swal({title: 'Please Select Birth Date'});
                return false;
            } else {
                return true;
            }
        }

        function val_emp_address(val) {
            if (val == '') {
                swal({title: 'Please Enter Address'});
                return false;
            } else {
                return true;
            }
        }

        function val_pin_code(val) {
            if (val == '') {
                swal({title: 'Please Enter Pin Code'});
                return false;
            } else {
                return true;
            }
        }

        function val_state(val) {
            if (val == '') {
                swal({title: 'Please Enter State'});
                return false;
            } else {
                return true;
            }
        }

        function val_alternate_number(val) {
            if (val == '') {
                swal({title: 'Please Enter Alternate Number'});
                return false;
            } else if (val.length > 10 || val.length < 10) {
                swal({title: 'Please Enter 10 digit Alternate Number'});
                return false;
            } else {
                return true;
            }
        }

        function val_emp_nationality(val) {
            if (val == '') {
                swal({title: 'Please Enter Nationality'});
                return false;
            } else {
                return true;
            }
        }

        function val_emp_religion(val) {
            if (val == '') {
                swal({title: 'Please Enter Religion'});
                return false;
            } else {
                return true;
            }
        }

        function val_martial_status(val) {
            if (val == '') {
                swal({title: 'Please Enter Martial Status'});
                return false;
            } else {
                return true;
            }
        }

        function val_person_name(val) {
            if (val == '') {
                swal({title: 'Please Enter Person Name'});
                return false;
            } else {
                return true;
            }
        }

        function val_relationship(val) {
            if (val == '') {
                swal({title: 'Please Enter Relationship'});
                return false;
            } else {
                return true;
            }
        }

        function val_phone_number(val) {
            if (val == '') {
                swal({title: 'Please Enter Emergency Phone Number'});
                return false;
            } else {
                return true;
            }
        }

        function val_eb_name(val) {
            if (val == '') {
                swal({title: 'Please Enter Bank Name'});
                return false;
            } else {
                return true;
            }
        }

        function val_eb_account_number(val) {
            if (val == '') {
                swal({title: 'Please Enter Account Number'});
                return false;
            } else {
                return true;
            }
        }

        function val_eb_ifsc_code(val) {
            if (val == '') {
                swal({title: 'Please Enter IFSC Code'});
                return false;
            } else {
                return true;
            }
        }

        function val_eb_pan_no(val) {
            if (val == '') {
                swal({title: 'Please Enter Pan No'});
                return false;
            } else {
                return true;
            }
        }

        function val_agree(val) {
            if (val == '') {
                swal({title: 'Please Read and Check Company Policy '});
                return false;
            } else {
                return true;
            }
        }

        // file upload here
        $(document).on('change', '#file', function () {
            //            swal({title:'"Inside Control");
            var name = document.getElementById("file").files[0].name;
            var form_data = new FormData();
            var ext = name.split('.').pop().toLowerCase();
            if (jQuery.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                swal({title: 'Invalid Image File'});
            }
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("file").files[0]);
            var f = document.getElementById("file").files[0];
            var fsize = f.size || f.fileSize;
            if (fsize > 200000) {
                swal({title: "Image File Size is very big"});
            } else {
                form_data.append("file", document.getElementById('file').files[0]);
                $.ajax({
                    url: "profile_image.php",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
                    },
                    success: function (data) {
                        $('#uploaded_image').html(data);
                        swal({title: data});
                        view_profile();
                    }
                });
            }
        });
        //
        //         // file upload end
        //
        function view_profile() {
            $.ajax({
                url: 'view_profile.php',
                success: function (data) {
                
                    $('#uploaded_image').html(data);
                }
            });
        }

        //
        function view_policy() {
            var admin_id = $('#admin_id').val();
            var dp_name = $('#dp_name').val();
            $.ajax({
                url: 'policy.php',
                method: "POST",
                data: {admin_id: admin_id, dp_name: dp_name},
                success: function (data) {
                    $('#view_policy').html(data);
                }
            });
        }
    </script>
    <?php
} else {
    header("Location:../index.php");
}
