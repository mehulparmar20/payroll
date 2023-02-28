
//Registration for Sub-Admin
function onsubmit()
{
    //first Step
//                    alert(10);
    var company_name = document.getElementById('company_name').value;
    var f_name = document.getElementById('first_name').value;
    var l_name = document.getElementById('last_name').value;
    var contact = document.getElementById('contact_number').value;
    var email = document.getElementById('company_email').value;
    var no_of_emeployee = document.getElementById('no_of_employee').value;

    $.ajax({
        url: "auth.php",
        method: "POST",
        data: {company_name: company_name, f_name: f_name, l_name: l_name, contact: contact,
            email: email, no_of_employee: no_of_emeployee, action: 'stepone'},
        datatype: "html",
        success: function (response)
        {
            alert(response);
            //window.location.href ='login.php';
        }
    });
}

function onsubmit1()
{
    var c_address = document.getElementById('company_address').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    $.ajax({
        url: "control.php",
        method: "POST",
        data: {c_address: c_address,
            username: username,
            password: password,
            action: 'steptwo'},
        datatype: "html",
        success: function (response)
        {
            alert(response);
            //window.location.href ='login.php';
        }
    });
}
function steplast()
{
    //Card Details
    var card_type = document.getElementById('cardtype').value;
    var card_hname = document.getElementById('card_holdername').value;
    var card_number = document.getElementById('card_number').value;
    var card_cvv = document.getElementById('card_cvv').value;
//                    var card_exdt = document.getElementById('card_exdt').value;
    var card_exdt = '10/22';
//                    console.log(card_type);
//                    console.log(card_hname);
//                    console.log(card_number);
//                    console.log(card_cvv);
//                    console.log(card_exdt);
//                    
    $.ajax({
        url: "control.php",
        method: "POST",
        data: {cardtype: card_type,
            card_holdername: card_hname,
            card_number: card_number,
            card_cvv: card_cvv,
            card_exdt: card_exdt,
            action: 'steplast'},
        datatype: "html",
        success: function (response)
        {
            alert(response);
            window.location.href = 'login.php';
        }
    });
}


//For Add New Employee
function addemp()
{
//        alert(10);
    var f_name = document.getElementById('f_name').value;
    var l_name = document.getElementById('l_name').value;
    var email = document.getElementById('email').value;
    var emp_id = document.getElementById('emp_id').value;
    var join_date = document.getElementById('join_date').value;
    var phone_no = document.getElementById('ph_no').value;
    var admin_id = document.getElementById('admin_id').value;
    var department = document.getElementById('department').value;
    if (f_name == null)
    {
        alert("Fill all The * Mark Fields");
    }
    else if (l_name == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (emp_id == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (join_date == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (phone_no == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (admin_id == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (department == '')
    {
        alert("Fill all The * Mark Fields");
    }
    else if (email == '' && email.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/))
    {
        alert("Enter valid Email");
    }
    else
    {
        $.ajax({
            url: "../control.php",
            type: "POST",
            data: {
                f_name: f_name,
                l_name: l_name,
                email: email,
                emp_id: emp_id,
                join_date: join_date,
                ph_no: phone_no,
                admin_id: admin_id,
                department: department,
                action: 'addemployee'},
            dataType: 'html',
            success: function (response)
            {
                alert(response);
                window.location.href = 'employees.php';
            }
        });
    }
}

// Edit Employee

//    function editemp()
//    {
////        alert(10);
//        var f_name = document.getElementById('f_name').value;
//        var l_name = document.getElementById('ul_name').value;
//        var email = document.getElementById('uemail').value;
//        var e_id = document.getElementById('ue_id').value;
//        var join_date = document.getElementById('ujoin_date').value;
//        var phone_no = document.getElementById('uph_no').value;
//        var admin_id = document.getElementById('uadmin_id').value;
//        var department = document.getElementById('udepartment').value;
//        if (f_name == null)
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (l_name == '')
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (join_date == '')
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (phone_no == '')
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (admin_id == '')
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (department == '')
//        {
//            alert("Fill all The * Mark Fields");
//        } else if (email == '' && email.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/))
//        {
//            alert("Enter valid Email");
//        } else
//        {
//            $.ajax({
//                url: "../control.php",
//                type: "POST",
//                data: {
//
//                    f_name: f_name,
//                    l_name: l_name,
//                    email: email,
//                    e_id: e_id,
//                    join_date: join_date,
//                    ph_no: phone_no,
//                    admin_id: admin_id,
//                    department: department,
//                    action: 'edit_employee'},
//                dataType: 'html',
//
//                success: function (response)
//                {
//                    alert(response);
////                          window.location.href='employees.php';
//                }
//            });
//        }
//    }

// Add New Holiday

function addholiday()
{

    var holiday_name = document.getElementById('holiday_name').value;
    var holiday_date = document.getElementById('holiday_date').value;
    var holiday_description = document.getElementById('holiday_description').value;
    var admin_id = document.getElementById('admin_id').value;

//      
    if (holiday_name == null && holiday_description == null && holiday_date == null)
    {
        alert("Please Fill The * Fields");
    }
    else
    {

        $.ajax({
            url: "../control.php",
            type: "POST",
            data: {
                holiday_name: holiday_name,
                holiday_date: holiday_date,
                holiday_description: holiday_description,
                admin_id: admin_id,
                action: 'addholiday'},
            datatype: "html",
            success: function (response)
            {

                window.location.href = 'holidays.php';
                setInterval('location.reload()', 3000);
            }
        });
    }
}



// add policies
function addpolicies()
{
    var policy = document.getElementById('policy').value;
    var description = document.getElementById('description').value;
    var department = document.getElementById('department').value;
    var admin_id = document.getElementById('department').value;

    alert(policy);

    $.ajax({
        url: "../control.php",
        type: "POST",
        data: {
            policy: policy,
            description: description,
            department: department,
            admin_id: admin_id,
            action: 'addpolicies'},
        datatype: "html",
        success: function (response)
        {
            window.location.href = 'policies.php';
        }
    });
}


// add policies
function designation()
{
    var designation_name = document.getElementById('designation_name').value;
    var department = document.getElementById('department').value;
    var admin_id = document.getElementById('admin_id').value;

    if (val_designation_name(designation_name)) {
        if (val_department(department)) {
            $.ajax({
                url: "../control.php",
                type: "POST",
                data: {
                    designation_name: designation_name,
                    department: department,
                    admin_id: admin_id,
                    action: 'designation'},
                datatype: "html",
                success: function (response)
                {
                    echo(response);
                    window.location.href = 'designations.php';
                }
            });
        }
    }
    function val_designation_name(val) {
        if (val == ''){
            alert('Please Write Designation');
            return false;
        }else{
            return true;
        }
    }
    function val_department(val) {
        if (val == 'no'){
            alert('Please Select Department');
            return false;
        }else{
            return true;
        }
    }
}

// add department
function adddepartment()
{
    var department_name = document.getElementById('departments_name').value;
    var admin_id = document.getElementById('admin_id').value;

//        alert(department_name);
    $.ajax({
        url: "../control.php",
        type: "POST",
        data: {
            department_name: department_name,
            admin_id: admin_id,
            action: 'adddepartment'},
        datatype: "html",
        success: function (response)
        {
            alert(response);
            window.location.href = 'departments.php';
        }
    });
}