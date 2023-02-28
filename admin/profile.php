<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = $_GET['name'];
    include 'admin_header.php';
    $sql = mysqli_query($conn, "SELECT * FROM `employee`INNER JOIN `departments` ON employee.department = departments.departments_id WHERE employee.e_id = '" . $_GET['id'] . "' AND employee.admin_id = '" . $_SESSION['admin_id'] . "' ");
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
                                    <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" >
                                    <img class="inline-block" src="../employee/employee_profile/<?php echo $row['employee_profile']; ?>">
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
                                                <small class="text-muted"><?php echo $row['departments_name']; ?></small>
                                                <div class="staff-id">Employee ID : <?php echo $row['emp_cardid']; ?></div>
                                                <div class="small doj text-muted">Date of Join : <?php echo date('d-m-Y', $row['join_date']); ?></div>
                                                <div class="staff-id">Email ID : <?php echo $row['e_email']; ?></div>
                                                <div class="staff-id">Phone : +91 <?php echo $row['e_phoneno']; ?></div>
                                            </div>
                                        </div>
                                        <!-- start -->
                                        <?php
                                        $e1 = mysqli_query($conn, "select * from employee_profile where e_id = '" . $_GET['id'] . "' AND admin_id = '" . $_SESSION['admin_id'] . "' ");
                                        $r1 = mysqli_num_rows($e1);
                                        if ($r1 != 1) {
                                            ?>
                                            <div class="col-md-7">
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Birthday:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Address:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Gender:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Nationality:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Religion:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Marital status:</div>
                                                        <div class="text">&nbsp;</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-7">
                                                <ul id="view_data2" class="personal-info">
                                                </ul>
                                            </div>
                                        <?php } ?>
                                        <!-- end -->
                                    </div>
                                </div>
                                <div class="pro-edit"><a class="edit-icon edit1" href="#"><i class="fa fa-pencil"></i></a></div>
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
                                    <?php $e2 = mysqli_query($conn, "select * from employee_emergemcy_contact where e_id = '" . $_GET['id'] . "' AND admin_id = '" . $_SESSION['admin_id'] . "' ");
                                        $r2 = mysqli_num_rows($e2);
                                        if ($r2 != 1) { ?>
                                        <h3 class="card-title">Emergency Contact <a href="#" class="edit-icon edit2"><i class="fa fa-pencil"></i></a></h3>
                                        <!--<h5 class="section-title">Primary</h5>-->
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Name</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                            <li>
                                                <div class="title">Relationship</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                            <li>
                                                <div class="title">Phone </div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                        </ul>
                                    <?php } else { ?>
                                        <h3 class="card-title">Emergency Contact <a href="#" class="edit-icon edit2"><i class="fa fa-pencil"></i></a></h3>
                                        <!--<h5 class="section-title">Primary</h5>-->
                                        <ul class="personal-info" id="view_data1">
                                        </ul>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <?php $e3 = mysqli_query($conn, "select * from employee_bank_detail where e_id = '" . $_GET['id'] . "' AND admin_id = '" . $_SESSION['admin_id'] . "' ");
                                        $r3 = mysqli_num_rows($e3);
                                        if ($r3 != 1) { ?>
                                        <h3 class="card-title">Bank information <a href="#" class="edit-icon edit3"><i class="fa fa-pencil"></i></a></h3>
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Bank name</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                            <li>
                                                <div class="title">Bank account No.</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                            <li>
                                                <div class="title">IFSC Code</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                            <li>
                                                <div class="title">PAN No</div>
                                                <div class="text">&nbsp;</div>
                                            </li>
                                        </ul>
                                    <?php } else { ?>
                                        <h3 class="card-title">Bank information <a href="#" data-target='modal3' class="edit-icon edit3"><i class="fa fa-pencil"></i></a></h3>
                                        <ul id="view_data3" class="personal-info">
                                        </ul>
                                    <?php } ?>
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
        <?php }  include 'footer.php'; ?>

<!-- Profile Modal -->
<div id="modal1" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form1" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Birth Date*</label>
                                        <div>
                                            <input class="form-control" type="date" id="date_of_birth">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender*</label>
                                        <select class="select form-control" id="emp_gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address*</label>
                                <input type="text" class="form-control" id="emp_address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nationality* <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="emp_nationality">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Religion*</label>
                                <div>
                                    <input class="form-control" type="text" id="emp_religion">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Marital status <span class="text-danger">*</span></label>
                                <select class="select form-control" id="martial_status">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                </select>
                                <input class="form-control" type="hidden" id="emp_pro_id">
                                <input class="form-control" type="hidden" id="e_id" value="<?php echo $_GET['id']; ?>">
                                <input class="form-control" type="hidden" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" id="insert">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Profile Modal -->

<!-- Emergency Contact Modal -->
<div id="modal2" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emergency Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form2" method="post">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="person_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Relationship <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="relationship">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" id="phone_number">
                                    <input class="form-control" type="hidden" id="er_id">
                                    <input class="form-control" type="hidden" id="e_id" value="<?php echo $_GET['id']; ?>">
                                    <input class="form-control" type="hidden" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>">
                                </div>
                            </div>
                        </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Emergency Contact Modal -->

<!-- Emergency Contact Modal -->
<div id="modal3" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form3" method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Name <span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="eb_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Account No. <span class="text-danger">*</span></label>
                                    <input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" type="text" id="eb_account_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IFSC Code <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="eb_ifsc_code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PAN No <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="eb_pan_no">
                                    <input class="form-control" type="hidden" id="eb_id">
                                    <input class="form-control" type="hidden" id="e_id" value="<?php echo $_GET['id']; ?>">
                                    <input class="form-control" type="hidden" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Emergency Contact Modal -->
<script>
    $(document).ready(function () {
        view_profile();
        view_profile_1();
        view_profile_2();
        view_profile_3();
    });
        $(document).on('click', '.edit1', function () {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee'},
                type: 'json',
                success: function (data) {
                    var j = JSON.parse(data);
                    $('#modal1').modal('show');
                    $('#date_of_birth').val(j[0].date_of_birth);
                    $('#emp_address').val(j[0].emp_address);
                    $('#emp_gender').val(j[0].e_gender);
                    $('#emp_nationality').val(j[0].emp_nationality);
                    $('#emp_religion').val(j[0].emp_religion);
                    $('#martial_status').val(j[0].martial_status);
                    $('#emp_pro_id').val(j[0].emp_pro_id);
                }
            });
        });

        $(document).on('click', '.edit2', function () {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee'},
                type: 'json',
                success: function (data) {
                    var j = JSON.parse(data);
                    $('#modal2').modal('show');
                    $('#person_name').val(j[2].person_name);
                    $('#relationship').val(j[2].relationship);
                    $('#phone_number').val(j[2].phone_number);
                    $('#er_id').val(j[2].er_id);

                }
            });
        });

        $(document).on('click', '.edit3', function () {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee'},
                type: 'json',
                success: function (data) {
                    var j = JSON.parse(data);
                    $('#modal3').modal('show');
                    $('#eb_name').val(j[1].eb_name);
                    $('#eb_account_number').val(j[1].eb_account_number);
                    $('#eb_ifsc_code').val(j[1].eb_ifsc_code);
                    $('#eb_pan_no').val(j[1].eb_pan_no);
                    $('#eb_id').val(j[1].eb_id);

                }
            });
        });

        $('#form1').on('submit', function (event) {
            event.preventDefault();
            var date_of_birth = $('#date_of_birth').val();
            var emp_address = $('#emp_address').val();
            var emp_nationality = $('#emp_nationality').val();
            var emp_religion = $('#emp_religion').val();
            var martial_status = $('#martial_status').val();
            var emp_gender = $('#emp_gender').val();
            var emp_pro_id = $('#emp_pro_id').val();
            var admin_id = $('#admin_id').val();
            var e_id = $('#e_id').val();
            if (val_birth(date_of_birth)) {
                if (val_emp_gender(emp_gender)) {
                    if (val_emp_address(emp_address)) {
                        if (val_emp_nationality(emp_nationality)) {
                            if (val_emp_religion(emp_religion)) {
                                if (val_martial_status(martial_status)) {
                                    $.ajax({
                                        url: "../control.php",
                                        method: "post",
                                        data: {date_of_birth: date_of_birth,
                                            emp_address: emp_address,
                                            emp_gender: emp_gender,
                                            emp_nationality: emp_nationality,
                                            emp_religion: emp_religion,
                                            martial_status: martial_status,
                                            emp_pro_id: emp_pro_id,
                                            e_id: e_id,
                                            admin_id: admin_id,
                                            action: 'profile1'},
                                        success: function (data)
                                        {
                                            $('#modal1').modal('hide');
                                            swal("Profile Setup Successfully");
                                            view_profile_1();
                                        }
                                    });
                                }
                            }
                        }
                    }
                }
            }
        });

        $('#form2').on('submit', function (event) {
            event.preventDefault();
            var person_name = $('#person_name').val();
            var relationship = $('#relationship').val();
            var phone_number = $('#phone_number').val();
            var er_id = $('#er_id').val();
            var admin_id = $('#admin_id').val();
            var e_id = $('#e_id').val();
            if (val_person_name(person_name)) {
                if (val_relationship(relationship)) {
                    if (val_phone_number(phone_number)) {
                        $.ajax({
                            url: "../control.php",
                            method: "post",
                            data: {person_name: person_name,
                                relationship: relationship,
                                phone_number: phone_number,
                                er_id: er_id,
                                e_id: e_id,
                                admin_id: admin_id,
                                action: 'profile2'},
                            success: function (data)
                            {
                                $('#modal2').modal('hide');
                                swal("Profile Setup Successfully");
                                view_profile_1();
                            }
                        });
                    }
                }
            }

        });

        $('#form3').on('submit', function (event) {
            event.preventDefault();
            var eb_name = $('#eb_name').val();
            var eb_account_number = $('#eb_account_number').val();
            var eb_ifsc_code = $('#eb_ifsc_code').val();
            var eb_pan_no = $('#eb_pan_no').val();
            var eb_id = $('#eb_id').val();
            var admin_id = $('#admin_id').val();
            var e_id = $('#e_id').val();
            if (val_eb_name(eb_name)) {
                if (val_eb_account_number(eb_account_number)) {
                    if (val_eb_ifsc_code(eb_ifsc_code)) {
                        if (val_eb_pan_no(eb_pan_no)) {
                            $.ajax({
                                url: "../control.php",
                                method: "post",
                                data: {eb_name: eb_name,
                                    eb_account_number: eb_account_number,
                                    eb_ifsc_code: eb_ifsc_code,
                                    eb_pan_no: eb_pan_no,
                                    eb_id: eb_id,
                                    e_id: e_id,
                                    admin_id: admin_id,
                                    action: 'profile3'},
                                success: function (data)
                                {
                                    $('#modal3').modal('hide');
                                    swal("Profile Setup Successfully");
                                    view_profile_3();
                                }
                            });
                        }
                    }
                }
            }
        });

        function val_birth(val)
        {
            if (val == '') {
                swal({ title:'Please Select Birth Date'});
                return false;
            } else {
                return true;
            }
        }

        function val_emp_gender(val)
        {
            if (val == '') {
                swal({title:'Please Select Gender'});
                return false;
            } else {
                return true;
            }
        }
        function val_emp_address(val)
        {
            if (val == '') {
                swal({title:'Please Enter Address'});
                return false;
            } else {
                return true;
            }
        }

        function val_pin_code(val)
        {
            if (val == '') {
                swal({title:'Please Enter Pin Code'});
                return false;
            } else if(val.length > 6){
                swal({title:'Please Enter 6 Digit Pin Code Only'});
            }else {
                return true;
            }
        }
        function val_state(val)
        {
            if (val == '') {
                swal({title:'Please Enter State'});
                return false;
            } else {
                return true;
            }
        }
        function val_alternate_number(val)
        {
            if (val == '') {
                swal({title:'Please Enter Alternate Number'});
                return false;
            } else {
                return true;
            }
        }
        function val_emp_nationality(val)
        {
            if (val == '') {
                swal({title:'Please Enter Nationality'});
                return false;
            } else {
                return true;
            }
        }
        function val_emp_religion(val)
        {
            if (val == '') {
                swal({title:'Please Enter Religion'});
                return false;
            } else {
                return true;
            }
        }
        function val_martial_status(val)
        {
            if (val == 'no') {
                swal({title:'Please Select Martial Status'});
                return false;
            } else {
                return true;
            }
        }
        function val_person_name(val)
        {
            if (val == '') {
                swal({title:'Please Enter Person Name'});
                return false;
            } else {
                return true;
            }
        }
        function val_relationship(val)
        {
            if (val == '') {
                swal({title:'Please Enter Relationship'});
                return false;
            } else {
                return true;
            }
        }
        function val_phone_number(val)
        {
            if (val == '') {
                swal({title:'Please Enter Emergency Phone Number'});
                return false;
            } else if(val.length > 10 || val.length < 10){
                swal({title:'Please Enter Valid Phone Number'});
            }else {
                return true;
            }
        }
        function val_eb_name(val)
        {
            if (val == '') {
                swal({title:'Please Enter Bank Name'});
                return false;
            } else {
                return true;
            }
        }
        function val_eb_account_number(val)
        {
            if (val == '') {
                swal({title:'Please Enter Account Number'});
                return false;
            } else {
                return true;
            }
        }
        function val_eb_ifsc_code(val)
        {
            if (val == '') {
                swal({title:'Please Enter IFSC Code'});
                return false;
            } else {
                return true;
            }
        }
        function val_eb_pan_no(val)
        {
            if (val == '') {
                swal({title:'Please Enter Pan No'});
                return false;
            } else {
                return true;
            }
        }

        // file upload here
        $(document).on('change', '#file', function () {
            var name = document.getElementById("file").files[0].name;
            var form_data = new FormData();
            var ext = name.split('.').pop().toLowerCase();
            if (jQuery.inArray(ext, ['png', 'jpg', 'jpeg']) == -1)
            {
                swal({title:"Invalid Image File"});
            }
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("file").files[0]);
            var f = document.getElementById("file").files[0];
            var fsize = f.size || f.fileSize;
            if (fsize > 200000)
            {
                swal({title:"Image File Size is very big"});
            }
            else
            {
                form_data.append("file", document.getElementById('file').files[0]);
                form_data.append("id", document.getElementById('id').value);
                $.ajax({
                    url: "profile_image.php",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#uploaded_image').html("<img  src='app/img/loder.gif' height='25' width='25' />");
                    },
                    success: function (data)
                    {
                        $('#uploaded_image').html(data);
                        swal({title:data});
                        view_profile();
                    }
                });
            }
        });
        // file upload end
        function view_profile_1()
        {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee_profile'},
                success: function (data) {
                    var get_code = JSON.parse(data);
                    $('#view_data1').html(get_code[2]);
                }
            });
        }

        function view_profile_2()
        {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee_profile'},
                success: function (data) {
                    var get_code = JSON.parse(data);
                    $('#view_data2').html(get_code[1]);
                }
            });
        }

        function view_profile_3()
        {
            var id = $("#id").val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'read.php',
                method: 'post',
                data: {id: id,admin_id:admin_id,action:'fetch_employee_profile'},
                success: function (data) {
                    var get_code = JSON.parse(data);
                    $('#view_data3').html(get_code[0]);
                }
            });
        }
        function view_profile()
        {
            var id = $('#id').val();
            var admin_id = $("#admin_id").val();
            $.ajax({
                url: 'view_profile.php',
                method: 'POST',
                data: {id: id,admin_id:admin_id},
                success: function (data) {
                    $('#uploaded_image').html(data);
                    view_profile_1();
                }
            });
        }

</script>
<?php
}
else
{
    header("Location:../index.php?msg=Your Session is Expired");
}
?>