var  login_counter = 0;
document.getElementById('free_trial').addEventListener('click', function(e){
    e.preventDefault();
    var fname = $("#f_name").val();
    var lname = $("#l_name").val();
    var company_name = $("#c_name").val();
    var phone_no = $("#phone").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var terms_condition = $("#terms_condition").val(); 
    const queryString = window.location.search;
    const parameters = new URLSearchParams(queryString);
    const value = parameters.get('plan');
    
    if(value =="Carrier" || value == 'Broker' || value == 'Trucker'){
        if(validateEmpty(fname,'f_name','First name')) {
            if(validateEmpty(lname,'l_name','Last name')) {
                if(validateEmpty(company_name,'c_name','Company name')) {
                    if(validateEmpty(phone_no,'phone','Phone')) {
                        if(validatePhone()) {
                            if(validateEmpty(email,'email','Email address')) {
                                if(validateEmail()) {
                                    if(validateEmpty(password,'password','Password')) {
    
                                        $(".loading").css("display", "inline-block");
                                        if (validate_form) {
                                            var data = {
                                                fname: fname,
                                                lname: lname,
                                                company_name: company_name,
                                                phone_no: phone_no,
                                                email: email,
                                                password: password,
                                                plan_id: value,
                                            };
                                            $.ajax({
                                                url: '../../Master.php',
                                                type: 'POST',
                                                dataType: "html",
                                                data: {
                                                    data: data,
                                                    sub: 'add_subscriptions',
                                                    main: 'register'
                                                },
                                                success: function (data) {
                                                    var data = data.trim();
                                                    if (data == 'success') {
                                                        swal("A verification link has been sent to your email", {
                                                            icon: "success",
                                                        });
                                                        window.location.href = "../../index.php";
                                                        $(".loading").css("display", "none");
                                                    }
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
    }else{
        window.location.href = "../plans.php";
    }
    
});

var handler = StripeCheckout.configure({
    key: 'pk_test_51GzePFHpxXHfgq9Upqmprvo61wT5AqVqoxC7fZ6CNh1Cb9BnFUvPkQA6S99hLHhoOShT9CNUKLIBg6XSpLniNIgL002t2u6s3J',
    image: 'http://windsonpayroll.com/images/payrollfavicon.png',
    locale: 'auto',
    currency: 'usd',
    token: function(token) {
        // Send the token in an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '//httpbin.org/post');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var resp = JSON.parse(xhr.responseText);
                var token = resp.form.token;
                pay_plan(token);
                alert("Payment received Successfully.");
                alert("Please do not Reload or Refresh the page.");
            } else if (xhr.status !== 200) {
                // alert('Request failed.  Returned status of ' + xhr.status);
            }
        };
        xhr.send(encodeURI('token=' + token.id));
    }
});
if (handler == true) {

}

function submit_pay() {
    var fname = $("#first_name").val();
    var lname = $("#last_name").val();
    var name = fname + " " + lname;
    var amount = getCookie("amount");
    amount = amount * 100;
    // Open Checkout with further options:
    handler.open({
        name: 'WINDSON DISPATCH',
        description: name,
        amount: amount
    });
}


// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
    handler.close();
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function pay_plan() {
    var fname = $("#first_name").val();
    var lname = $("#last_name").val();
    var company_name = $("#company_name").val();
    var phone_no = $("#phone_number").val();
    var email = $("#email").val();
    var business_type = $("#business_type").val();
    var total_user = $("#total_user").val();
    var terms_condition = $("#terms_condition").val();
    var username = $("#user_name").val();
    var password = $("#password1").val();
    var country = $("#country").val();
    var state = $("#states").val();
    var town = $("#town").val();
    var address = $("#address").val();
    var zip = $("#zip").val();
    var mc_num = $("#mc_num").val();
    var ff_num = $("#ff_num").val();
    var data = {
        fname: fname,
        lname: lname,
        company_name: company_name,
        phone_no: phone_no,
        email: email,
        business_type: business_type,
        total_user: total_user,
        terms_condition: terms_condition,
        username: username,
        password: password,
        country: country,
        state: state,
        town: town,
        address: address,
        zip: zip,
        mc_num: mc_num,
        ff_num: ff_num,
    };

    $.ajax({
        url: '../Master.php',
        type: 'POST',
        dataType: "html",
        data: {
            data: data,
            sub: 'add_subscriptions',
            main: 'register'
        },
        success: function(data) {
            // console.log(data);
            var data = data.trim();
            if (data == 'success') {
                window.location.href = "./info.php?email=" + email;
            }
        }
    });
}

function startTimer(duration, display) {
    var timer = duration,
        minutes, seconds;
    setInterval(function() {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

function register_details() {
    var fname = $("#first_name").val();
    var lname = $("#last_name").val();
    var company_name = $("#company_name").val();
    var phone_no = $("#phone_number").val();
    var email = $("#email").val();
    var business_type = $("#business_type").val();
    var total_user = $("#total_user").val();
    var terms_condition = $("#terms_condition").val();
    var username = $("#user_name").val();
    var password = $("#password1").val();
    var country = $("#country").val();
    var state = $("#states").val();
    var town = $("#town").val();
    var address = $("#address").val();
    var zip = $("#zip").val();
    var mc_num = $("#mc_num").val();
    var ff_num = $("#ff_num").val();
    var plan_id = getCookie('plan_id');
    var id = $("#id").val();
    var data = {
        fname: fname,
        lname: lname,
        company_name: company_name,
        phone_no: phone_no,
        email: email,
        business_type: business_type,
        total_user: total_user,
        username: username,
        password: password,
        country: country,
        state: state,
        town: town,
        address: address,
        zip: zip,
        mc_num: mc_num,
        ff_num: ff_num,
        id: id,
        plan_id: plan_id,
    };
    $.ajax({
        url: '../../Master.php',
        type: 'POST',
        dataType: "html",
        data: {
            data: data,
            sub: 'add_registration',
            main: 'register'
        },
        success: function(data) {
            var id = data.trim();
            $("#id").val(id);
        }
    });
}

function movenext(curr, next) {
    document.getElementById(next).focus();
}

function companyLogin() {

localStorage.removeItem('welcometost');


    var companyEmail = document.getElementById('companyEmail').value;
    var companyPassword = document.getElementById('companyPassword').value;
    var data = {
        companyEmail: companyEmail,
        companyPassword: companyPassword,
    };
    if (val_loginCompanyEmail(companyEmail)) {
        if (val_loginCompanyPassword(companyPassword)) {
            $(".loading").css("display", "inline-block");
            $.ajax({
                url: '../../Master.php',
                dataType: "html",
                type: 'POST',
                data: {
                    data: data,
                    sub: 'login_request',
                    main: 'register'
                },
                success: function (data) {
                    var get = JSON.parse(data);
                    // console.log(get);
                    if (login_counter == 3) {
                        swal({
                            title: "Do you want to forgot password?",
                            text: "if you want, click OK.!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    window.location.href = "forgot-password.php";
                                } else {
                                    login_counter = 0;
                                }
                            });
                    }
                    if (get.status == "valid") {
                        // alertify
                        //     .delay(600000)
                        //     .log("OTP Expires in <span id='extime'></span>");

                        // var fiveMinutes = 60 * 10,
                        //     display = document.querySelector('#extime');
                        // startTimer(fiveMinutes, display);

                        if(get.otpsendto == 'admin') {
                            $(".loading").css("display", "none");
                            alertify.delay(10000).success("Get your OTP form Admin"); 
                            $("#id").val(get.id);
                            $("#otp").modal('show');
                            $("#otpdata").val(get.otp);
                        } else {
                            $(".loading").css("display", "none");
                            $("#id").val(get.id);
                            $("#otp").modal('show');
                            $("#otpdata").val(get.otp);
                        }
                    } else if (get.status == 'invalid') {
                        login_counter++;
                        $(".loading").css("display", "none");
                        if (login_counter < 4) {
                            swal({ icon: "warning", title: "Your email or password is invalid" });
                        }
                    } else if (get.status == 'plan_expired') {
                        $(".loading").css("display", "none");
                        swal({ title: "Your Plan Expired..!! Please renew your Subscription." });
                    } else if (get.status == 'accountdeleted') { // admin delete user account
                        $(".loading").css("display", "none");
                        swal({ title: "Invalid credentials for login." });
                    } else if (get.status == "Emailnotverify") {
                        $(".loading").css("display", "none");
                        var data = {
                            name: get.name,
                            token: get.token,
                            id: get.id,
                            email: get.email,
                            _id: get._id,
                            user_type: get.user_type
                        };
                        swal({
                            title: "Your Email-Id is not verifyed. Verify your Id for Login!",
                            text: "if you want another verification link, click OK.",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true, 
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    // console.log(data);
                                    $(".loading").css("display", "inline-block");
                                    $.ajax({
                                        url: '../../Master.php',
                                        dataType: "html",
                                        type: 'POST',
                                        data: {
                                            data: data,
                                            sub: 'email_verify',
                                            main: 'register'
                                        },
                                        success: function (data) {
                                            // console.log(data);
                                            var get = data.trim();
                                            if (get == 'success') {
                                                $(".loading").css("display", "none");
                                                swal("Verification link send to your Email-Id!", {
                                                    icon: "success",
                                                });
                                            } else {
                                                swal({ title: "Email not send successfully..!" });
                                            }
                                        }
                                    });
                                }
                            });
                    }
                }
            });
        }
    }
}
function val_loginCompanyEmail(val) {
    if (val == '') {
        swal({ title: 'Please Enter an Email' })
            .then(value => {
                $("#companyEmail").focus();
            });
        return false;
    } else {
        return true;
    }
}

function val_loginCompanyPassword(val) {
    if (val == '') {
        swal({ title: 'Please Enter an Password' })
            .then(value => {
                $("#companyPassword").focus();
            });
        return false;
    } else {
        return true;
    }
}

function val_otp(val) {
    if (val.length != 6) {
        swal({ title: 'Please Enter an OTP' })
            .then(value => {
                $("#first").focus();
            });
        return false;
    } else {
        return true;
    }
}


function otpverify() {

    var otp = $("#otpdata").val();
    var id = $("#id").val();
    var data = {
        otp: otp,
        id: id,
    };
    $.ajax({
        url: '../../Master.php',
        dataType: "html",
        type: 'POST',
        data: {
            data: data,
            sub: 'otp_verify',
            main: 'register'
        },
        success: function(data) {
            var data = data.trim();
            if (data == "user") {
                window.location = "../../Dashboard.php";
                // setTimeout(function(){ mainMessageToast('Important Note*', 'We are facing an issue with email'); console.log("calledTost"); }, 9999);
            } else if (data == "expired") {
                swal({ title: "OTP is Expired.!! Please login again" })
                    .then((value) => {
                        window.location.href = "login.php";
                    });
            } else if (data == "nouser") {
                swal({ title: "Invalid OTP." })
                    .then((value) => {
                        $("#otpdata").val("");
                    });
            }
        }
    });
}
// function mainMessageToast(headermsg, infomsg) {
//     console.log('TostCalled');
//     $('#imp_element').removeClass('hide');
//     $('#imp_element').addClass('show');
//     $('#main_title').html(headermsg);
//     $('#info_msg').html(infomsg);
// }
$('.tost-close').click(function(){
    $('#imp_element').removeClass('show');
    $('#imp_element').addClass('hide');
});

function val_forgotOtp(val) {
    if (val.length != 6) {
        swal({ title: 'Please Enter an Valid OTP' })
            .then(value => {
                $("#otpdata").focus();
            });
        return false;
    }
    if (val == '') {
        swal({ title: 'Please Enter an OTP' })
            .then(value => {
                $("#otpdata").focus();
            });
        return false;
    }
    return true;
}

function verifyForgot() {
    $(".loading").css("display", "inline-block");
    var otp = $("#otpdata").val();
    var id = $("#id").val();
    if (val_forgotOtp(otp)) {
        var data = {
            otp: otp,
            id: id,
        };
        $.ajax({
            url: '../../Master.php',
            dataType: "html",
            type: 'POST',
            data: {
                data: data,
                sub: 'verifyforgot',
                main: 'register'
            },
            success: function(data) {
                var data = data.trim();
                if (data == "verify") {
                    $(".loading").css("display", "none");
                    $("#otp").modal('hide');
                    $("#changepass").modal('show');
                    // window.location = "./login.php";
                } else if (data == "expired") {
                    $(".loading").css("display", "none");
                    swal({ title: "OTP is Expired.!! Please login again" })
                        .then((value) => {
                            window.location.href = "login.php";
                        });
                } else if (data == "nouser") {
                    $(".loading").css("display", "none");
                    swal({ title: "Invalid OTP." })
                        .then((value) => {
                            $("#otpdata").val("");
                        });
                } else if (data == 'error') {
                    $(".loading").css("display", "none");
                    swal({ title: "Invalid OTP." })
                        .then((value) => {
                            $("#otpdata").val("");
                        });
                }
            }
        });
    }
}

function val_newPass(val) {
    if (val == '') {
        swal({ title: 'Please Enter an New Password' })
            .then(value => {
                $("#newpass").focus();
            });
        return false;
    } else {
        return true;
    }
}

function val_rePass(val) {
    if (val == '') {
        swal({ title: 'Please Enter an Re-Password' })
            .then(value => {
                $("#repass").focus();
            });
        return false;
    } else {
        return true;
    }
}

function val_pass(val1, val2) {
    if (val1 != val2) {
        swal({ title: 'Password not match' })
            .then(value => {
                $("#newpass").focus();
            });
        return false;
    } else {
        return true;
    }
}

function changePass() {
    var newpass = $("#newpass").val();
    var repass = $("#repass").val();
    if (val_newPass(newpass)) {
        if (val_rePass(repass)) {
            if (val_pass(newpass, repass)) {
                var id = $("#id").val();
                // console.log(id);
                var data = {
                    newpass: newpass,
                    id: id,
                };
                $(".loading").css("display", "inline-block");
                $.ajax({
                    url: '../../Master.php',
                    dataType: "html",
                    type: 'POST',
                    data: {
                        data: data,
                        sub: 'changepass',
                        main: 'register'
                    },
                    success: function(data) {
                        var data = data.trim();
                        if (data == "success") {
                            $(".loading").css("display", "none");
                            swal({ title: "Password changed successfuly!" })
                                .then((value) => {
                                    window.location = "./login.php";
                                });
                        } else if (data == "fail") {
                            $(".loading").css("display", "none");
                            swal({ title: "Password not set" })
                                .then((value) => {
                                    $("#newpass").val('');
                                    $("#repass").val('');
                                    // window.location.href = "forgot-password.php";
                                });
                        }
                    }
                });
            }
        }
    }
}

function forgotRequest() {
    $(".loading").css("display", "inline-block");
    var email = document.getElementById('email').value;
    var data = {
        email: email,
    };

    $.ajax({
        url: '../../Master.php',
        dataType: "html",
        type: 'POST',
        data: {
            data: data,
            sub: 'forgot',
            main: 'register'
        },
        success: function(data) {
            var get = JSON.parse(data);
            if (get.status == "valid") {
                $(".loading").css("display", "none");
                $("#id").val(get.id);
                $("#otp").modal('show');
            } else if (get.status == 'invalid') {
                $(".loading").css("display", "none");
                swal({ title: "Your email is invalid" });
                //window.location="./index.php";
            } else if (get.status == 'plan_expired') {
                $(".loading").css("display", "none");
                swal({ title: "Your Plan Expired..!! Please renew your Subscription." });
            }
        }
    });
}
// validate Token
function authJWT(token) {
    $.ajax({
        url: '../../../Master.php',
        type: 'POST',
        data: {
            main: 'notification',
            sub: "validatetoken",
            data: token
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data.message == 'success.') {
                changeEmailStatus(data.data.companyid, data.data._id);
                window.location.href = "../../reg/login.php";
            } else {
                swal({ title: "Email not Verifyed ..!! Please Try Again." });
            }
        }
    });
}
function changeEmailStatus(cid, docid) {
    // console.log(cid, docid);
    var data = {
        companyid: cid,
        docid: docid
    };
    $.ajax({
        url: '../../../Master.php',
        type: 'POST',
        data: {
            main: 'register',
            sub: "changeEmailStatus",
            data: data
        },
        success: function (data) {
            var data = data.trim();
        }
    });
}



function checkEmail(val) {
    
    $('#email').removeClass('email_class_suc');
    $('#invemail').removeClass('email_succ');
    if(val.includes("@")) {
        if(val.includes(".")) {
            setTimeout(function(){ 
               if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)) {
                    var data = {
                        verifyEmail : val
                    }
                    $.ajax({
                        url: './../../Master.php',
                        type: 'POST',
                        data: {
                            main: 'register',
                            sub: "checkemail",
                            data: data
                        },
                        success: function (data) {
                            var validate = data.trim();
                            if(validate == 'HasEmail') {
                                $("#free_trial").prop('disabled', true); 
                                $('#check_msg').addClass('email_error');
                                document.getElementById('check_msg').innerHTML = '<small>This email address has allready taken</small>';
                                $('#email').removeClass('email_class_suc');
                                $('#invemail').removeClass('email_succ');
                                $('#email').addClass('email_class_err');
                                $('#invemail').addClass('email_error');
                            } else {
                                $("#free_trial").prop('disabled', false); 
                                $('#check_msg').empty();
                                $('#email').removeClass('email_class_err');
                                $('#invemail').removeClass('email_error');
                                $('#email').addClass('email_class_suc');
                                $('#invemail').addClass('email_succ');
                            }
                        }
                    });
                } else {
                    $('#check_msg').addClass('email_error');
                    document.getElementById('check_msg').innerHTML = '<small>Enter valid email</small>';
                    $('#email').removeClass('email_class_suc');
                    $('#invemail').removeClass('email_succ');
                    $('#email').addClass('email_class_err');
                    $('#invemail').addClass('email_error');
                }
             }, 1500);
        }
    }
}
// for closing the alertfy of login counter
function closealertfy() {

    // console.log('called');
    $('.alertify-logs').empty();
}