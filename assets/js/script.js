var stripe = '';

$(document).ready(function () {
    var w = window.innerWidth;

    if (w > 767) {
        $('#menu-jk').scrollToFixed();
    } else {
        $('#menu-jk').scrollToFixed();
    }
})

$(document).ready(function () {
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        autoplay: true,
        dots: true,
        autoplayTimeout: 5000,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
});

$(document).on("click", "#login-btn", function (e) {
    e.preventDefault();
    const useremail = $("#useremail").val();
    const password = $("#userpass").val();
    const type = $("input[type='radio'][name='usertype']:checked").val();
    const data = {
        useremail: useremail,
        password: password,
        type: type,
    };
    if(checkvalid(type,'Select login type', true)){
        if(checkvalid(useremail,'your login email')){
            if(checkvalid(password,'your login password')){
                $.ajax({
                    url: "auth.php",
                    method: "POST",
                    data: {
                        module: "login",
                        request: 'register',
                        data: data
                    },
                    datatype: "html",
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (type == 'admin') {
                            if (data.responsemessage == 'success') {
                                $("#login-section").hide();
                                $("#otp-section").show();
                                $("#user-type").val(data.type);
                                $("#myemail-id").val(useremail);
                                $("#user-id").val(data.id);
                            } else {
                                swal({
                                    title: data.message,
                                    text: '',
                                    icon: "info",
                                    button: "ok!",
                                });
                            }
                        } else {
                            if (data.responsemessage == 'success') {
                                window.location.href = 'employee/home.php'
                            } else {
                                swal({
                                    title: data.message,
                                    text: '',
                                    icon: "info",
                                    button: "ok!",
                                });
                            }
                        }
            
                    }
                });
            }
        }
    }
});

function getParam(param) {
    return new URLSearchParams(window.location.search).get(param);
}

fetch("stripe/config.php")
    .then(function (result) {
        return result.json();
    })
    .then(function (json) {
        // console.log(json);
        var publishableKey = json.publishableKey;
        stripe = Stripe(publishableKey);

    });
$(document).on("keyup", "#noofUser", function (evt) {
    
        setTimeout(function(){
            const quntity = $('#noofUser').val();
            if(quntity < 5){
                $('#noofUser').val(5)
            }
        },3000);
    
});

$(document).on("click", "#checkout-button", async function (evt) {
    const plan = getParam('plan');
    const name = $('#adminname').val();
    const comName = $('#comName').val();
    const adminemail = $('#adminemail').val();
    const userpass = $('#userpass').val();
    const quntity = $('#noofUser').val();
    const admincontact = $('#admincontact').val();
    var data = {
        plantype: plan,
        adminname: name,
        comName: comName,
        adminemail: adminemail,
        userpass: userpass,
        quntity: quntity,
        admincontact: admincontact
    }
    if(checkvalid(name,'your name')){
        if(checkvalid(comName,'your company name')){
            if(valemail(adminemail)){
                let resdata = await emailexist(adminemail);
                const status = JSON.parse(resdata).status;
                if(status == 'new'){
                    if(valcontact(admincontact)){
                        if(checkvalid(userpass,'your strong password',true)){
                            if(checkvalid(quntity,' number of employees')){
                                $.ajax({
                                    url: "auth.php",
                                    type: "POST",
                                    data: {
                                        request: "register",
                                        module: "create-checkout",
                                        data: data
                                    },
                                    success: function (data) {
                                        console.log(data);
                                        stripe
                                            .redirectToCheckout({
                                                sessionId: data.trim()
                                            })
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

function checkvalid(val,name,status){
    if(val != ''){
        return true;
    }else{
        if(status){
            swal(`${name}`, `This field requied `, "info");
        }else{
            swal(`Enter ${name}`, `This field requied`, "info");    
        }
        return false;
    }
}

// function checkminvalid(val){
//     if(val != ''){
//         if(val > 5){
//             swal(`User must be minimum 5`, `This field requied `, "info");
//         }else{
//             swal(`Enter ${name}`, `This field requied`, "info");    
//         }
//         return false;
//     }
// }

$(document).on("click", "#otpSubmit-btn", function (e) {
    e.preventDefault();
    const otp = $("#userotp").val();
    const type = $("#user-type").val();
    const userId = $("#user-id").val();
    const data = {
        otp: otp,
        id: userId,
        type: type,
    };
    $.ajax({
        url: "auth.php",
        method: "POST",
        data: {
            module: "verifyOtp",
            request: 'register',
            data: data
        },
        datatype: "html",
        success: function (response) {
            var data = JSON.parse(response);
            if (data.responsemessage == 'success') {
                window.location.href = 'admin/index.php';
            } else {
                swal({
                    title: data.message,
                    text: '',
                    icon: "info",
                    button: "ok!",
                });
            }

        }
    });
});


$(document).on("click", "#forgototpSubmit-btn", function (e) {
    e.preventDefault();
    const otp = $("#userotp").val();
    const type = $("#user-type").val();
    const userId = $("#user-id").val();
    const data = {
        otp: otp,
        id: userId,
        type: type,
    };
    $.ajax({
        url: "auth.php",
        method: "POST",
        data: {
            module: "verifyforgotrequest",
            request: 'register',
            data: data
        },
        datatype: "html",
        success: function (response) {
            console.log(response);
            var data = JSON.parse(response);
            if (data.responsemessage == 'success') {
                $("#otp-section").hide();
                $("#password-section").show();
            } else {
                swal({
                    title: data.message,
                    text: '',
                    icon: "info",
                    button: "ok!",
                });
            }

        }
    });
});

$(document).on("click", "#recover-btn", function (e) {
    e.preventDefault();
    const recoveremail = $("#recoveremail").val();
    
    $.ajax({
        url: "auth.php",
        method: "POST",
        data: {
            module: "forgotpassword",
            request: 'register',
            email: recoveremail
        },
        datatype: "html",
        success: function (response) {
            console.log(response);
            var data = JSON.parse(response);
            if (data.status == 'success') {
                    $("#login-section").hide();
                    $("#otp-section").show();
                    $("#user-type").val(data.type);
                    $("#myemail-id").html(data.email);
                    $("#user-id").val(data.id);
                } else {
                    swal({
                        title: data.message,
                        text: '',
                        icon: "info",
                        button: "ok!",
                    });
                }

        }
    });
});

$(document).on("click", "#changepass-btn", function (e) {
    e.preventDefault();
    const newpass = $("#newpass").val();
    const confirmpass = $("#confirmpass").val();
    const type = $("#user-type").val();
    const userId = $("#user-id").val();
    if(newpass == confirmpass){
        const data = {
            newpass: newpass,
            id: userId,
            type: type,
        };
        $.ajax({
            url: "auth.php",
            method: "POST",
            data: {
                module: "changepass",
                request: 'register',
                data: data
            },
            datatype: "html",
            success: function (response) {
                console.log(response);
                var data = JSON.parse(response);
                if (data.status == 'success') {
                        swal({
                            title: "Password change successfully.",
                            text: '',
                            icon: "success",
                            button: "ok!",
                        });
                        setTimeout(function(){
                         	window.location.href = "index.php"; 
                        }, 2000);//wait 2 seconds
                    } else {
                        swal({
                            title: data.message,
                            text: '',
                            icon: "info",
                            button: "ok!",
                        });
                    }
    
            }
        });
    }else{
        swal({
                title: 'Password not match',
                text: '',
                icon: "info",
                button: "ok!",
            });
    }    
});

function emailexist(email){
    if(status){
        if(email == currentemail){
            return JSON.stringify({status : "new"});
        }
    }
    return $.ajax({
        url: "admin/read.php",
        type: "POST",
        async:false,
        data: {
            action: "emailexist",
            email: email
        },
        success: function (response) {
            let resdata = JSON.parse(response);
            if(resdata.status == 'new'){
                return "not exists";
            }else{
                swal("Email already exists in system.","","info");
                return "exists";
            }
        }
    });
}

function valcontact(val)
{
    if (val == '') {
        swal('Please Enter Phone Number',"","info");
        return false;
    } else if(val.length > 10 || val.length < 10){
        swal('Please Enter 10 Digits Only',"","info")
    } else if(isNaN(val)){
        swal('Enter a number only',"","info")
    }else {
        return true;
    }
}

function valemail(val) {
    if (val == '') {
        swal('Enter your email','','info');
        return false;
    } else if (val.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        swal('Enter Valid Email',"","info");
        return false;
    } else {
        return true;
    }
}
