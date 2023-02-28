<?php
//session start
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    //include header.php file for header
    include'admin_header.php';
    ?>
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Company Settings</h3>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Company Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="company_name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input class="form-control " id="admin_name" type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" id="company_address" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Country</label>
                            <input class="form-control" id="country" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control" id="city" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>State/Province</label>
                            <input class="form-control" id="state" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input class="form-control" id="pin_code" type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" id="admin_email" type="email">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input class="form-control" id="admin_contact" type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fax</label>
                            <input class="form-control" id="fax" type="text">
                            <input type="hidden" id="admin_id" value="<?php echo $_SESSION['admin_id']; ?>" >
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" onclick="company_details()" id="insert">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    <?php   include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        fetch_company_profile();
         });
         let companyemail = '';
        function fetch_company_profile()
        {
            var admin_id = $('#admin_id').val();
            $.ajax({
                url: '../control.php',
                method: 'POST',
                data: {admin_id: admin_id, action: 'company_profile'},
                dataType: 'json',
                success: function (data) {
                    $('#company_name').val(data.company_name);
                    $('#company_address').val(data.company_address);
                    $('#admin_name').val(data.admin_name);
                    $('#admin_contact').val(data.admin_contact);
                    companyemail = data.admin_email;
                    $('#admin_email').val(data.admin_email);
                    $('#country').val(data.country);
                    $('#city').val(data.city);
                    $('#state').val(data.state);
                    $('#pin_code').val(data.pin_code);
                    $('#fax').val(data.fax);
                }
            });
        }

        async function company_details() {
            var company_name = $('#company_name').val();
            var company_address = $('#company_address').val();
            var admin_name = $('#admin_name').val();
            var admin_contact = $('#admin_contact').val();
            var admin_email = $('#admin_email').val();
            var country = $('#country').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var pin_code = $('#pin_code').val();
            var fax = $('#fax').val();
            var admin_id = $('#admin_id').val();
            if (val_company_name(company_name)) {
                if (val_admin_name(admin_name)) {
                    if (val_company_address(company_address)) {
                        if (val_country(country)) {
                            if (val_city(city)) {
                                if (val_state(state)) {
                                    if (val_pin_code(pin_code)) {
                                        if (val_admin_email(admin_email)) {
                                            let resdata = await emailexist(admin_email,companyemail,true);
                                            const status = JSON.parse(resdata).status;
                                            if(status == 'new'){
                                                if (val_admin_contact(admin_contact)) {
                                                    $.ajax({
                                                        url: '../control.php',
                                                        method: 'POST',
                                                        data: {company_name: company_name,
                                                            admin_name: admin_name,
                                                            company_address: company_address,
                                                            country: country,
                                                            city: city,
                                                            state: state,
                                                            pin_code: pin_code,
                                                            admin_email: admin_email,
                                                            admin_contact: admin_contact,
                                                            fax: fax,
                                                            admin_id: admin_id,
                                                            action: 'edit_company_profile'},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            swal("Company Details Edit Successfully.","","success");
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

        function val_company_name(val)
        {
            if (val == '') {
                swal('Please Write Company Name',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_admin_name(val)
        {
            if (val == '') {
                swal('Please Write Your Name',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_company_address(val)
        {
            if (val == '') {
                swal('Please Write Company Address',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_country(val)
        {
            if (val == '') {
                swal('Please Write Country',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_city(val)
        {
            if (val == '') {
                swal('Please Write City',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_state(val)
        {
            if (val == '') {
                swal('Please Write State',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_pin_code(val)
        {
            if (val == '') {
                swal('Please Write Pin Code',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_admin_email(val)
        {
            if (val == '') {
                swal('Please Write Email',"","info");
                return false;
            } else {
                return true;
            }
        }
        function val_admin_contact(val)
        {
            if (val == '') {
                swal('Please Enter Phone Number',"","info");
                return false;
            } else if(val.length > 10 || val.length < 10){
                swal('Please Enter 10 Digits Only',"","info")
            }else {
                return true;
            }
        }
</script>
<?php
}
else
{
    header("Location:../index.php");
}