<?php
if(isset($_GET['planId'])){
session_start();
require 'register/config.php';
require 'register/Register.php';
require 'dbconfig.php';

$keyobj = new keys();
$keys = $keyobj->getRazorKeys();
$key_id = $keys['key'];
$planId = $_GET['planId'];
$comName = "WINDSON PAYROLL";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            font-family: 'Open Sans', sans-serif;
        }

        body{
            background: url("https://i.imgur.com/LAfQyOR.jpg") no-repeat top center;
            background-size: cover;
            width: 100%;
            height: 100vh;
            font-size: 16px;
        }

        .wrapper{
            width: 100%;
            padding: 10px;
        }

        .container{
            max-width: 500px;
            height: auto;
            margin: 0px auto;
            background: #fff;
            padding: 20px 40px 30px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top: 7px solid #5f6c7d;
        }

        .title{
            color: #5f6c7d;
            font-size: 2em;
            text-align: center;
            text-transform: uppercase;
            font-weight: 900;
            margin-bottom: 20px;
        }

        .input-form{
            margin-bottom: 20px;
        }

        .input-form .label{
            display: block;
            font-size: 1em;
            color: #212121;
            text-transform: capitalize;
            margin-bottom: 8px;
        }

        .input-form .items{
            margin-bottom: 20px;
        }

        .section-1 .items .input,
        .section-2 .items .input,
        .section-3 .items .input{
            background: transparent;
            border: 2px solid #BDBDBD;
            width: 100%;
            padding: 10px;
            font-size: 1em;
            color: #212121;
            border-radius: 3px;
        }

        .input-form .section-3{
            display: flex;
            justify-content: space-between;
        }

        .input-form .section-3 .items{
            width: 48%;
        }

        .btn{
            background: #5f6c7d;
            color: #fff;
            font-size: 1.25em;
            padding: 10px 0;
            text-align: center;
            text-transform: uppercase;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .cvc{
            display: flex;
            justify-content: space-between;
            position: relative;
            transition: all 0.3s ease;
        }

        .cvc .tooltip{
            color: #d4d4d4;
            border: 2px solid #d4d4d4;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align:center;
            cursor: pointer;
        }

        .cvc .cvc-img{
            position: absolute;
            top: -300%;
            right: 0;
            background: #5f6c7d;
            padding: 12px;
            border-radius: 5px;
            display: none;
        }

        .cvc .cvc-img img{
            width: 100px;
            height: auto;
            display: block;
        }

        .cvc:hover .cvc-img{
            display: block;
        }

        @media screen and (max-width: 460px){
            .input-form .section-3{
                flex-direction: column;
            }
            .input-form .section-3 .items{
                width: 100%;
            }
            .input-form .items{
                margin-bottom: 10px;
            }

            .input-form{
                margin-bottom: 15px;
            }
            .title{
                font-size: 1.5em;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="title">Checkout Form</div>

        <div class="input-form">
            <div class="section-1">
                <div class="items">
                    <label class="label">Admin name</label>
                    <input type="text" class="input" id="adminName" placeholder="Name">
                </div>
            </div>
            <div class="section-1">
                <div class="items">
                    <label class="label">Company name</label>
                    <input type="text" class="input" id="companyName" placeholder="Name">
                </div>
            </div>
            <div class="section-2">
                <div class="items">
                    <label class="label">Company email</label>
                    <input type="text" id="companyEmail" class="input" placeholder="Email">
                </div>
            </div>
            <div class="section-2">
                <div class="items">
                    <label class="label">Company contact</label>
                    <input type="text" class="input" id="companyContact" maxlength="10" data-mask="0000 0000 0000 0000" placeholder="1234 1234 1234 1234">
                </div>
            </div>
            <div class="section-2">
                <div class="items">
                    <label class="label">Total Employees</label>
                    <input type="number" class="input" id="totalEmployee"  placeholder="Total employee">
                </div>
            </div>
        </div>
        <button class="btn" id="rzp-button1">Pay</button>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js" ></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
    var orderobject = '';
    var optionobj = '';
    function getoptionobj(obj){
        var name = document.getElementById('companyName').value;
        var email = document.getElementById('companyEmail').value;
        var contact = document.getElementById('companyContact').value;
        var amount = obj.amount;
        var orderId = obj.id;
        let currency = obj.currency;
        var options = {
            "key": "<?php echo $key_id; ?>", // Enter the Key ID generated from the Dashboard
            "amount": amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": currency,
            "name": "<?php echo $comName; ?>",
            "description": "Premium",
            "image": "https://new.windsonpayroll.com/admin/app/img/payroll.png",
            "order_id": orderId, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function (response){
            },
            "prefill": {
                "name": name,
                "email": email,
                "contact": contact
            },
            "theme": {
                "color": "#611292"
            }
        };
        return options;
    }

    function createOrder() {
        var totalEmployee = document.getElementById('totalEmployee').value;
        let obj = {
            "totalEmployee" : totalEmployee,
            "planId" : "<?php echo $planId; ?>"
        }
        $.ajax({
            url: "auth.php",
            method: "POST",
            async : false,
            data: {
                data: obj,
                'request': "register",
                'module': 'createOrder',
                'reqtype' : "create Ordre"
            },
            datatype: "html",
            success: function (response){
                console.log(response);
                orderobject = JSON.parse(response);
            }
        });
    }

    function getValues(obj){
        alert("Called")
        var companyname = document.getElementById('companyName').value;
        var adminname = document.getElementById('adminName').value;
        var email = document.getElementById('companyEmail').value;
        var contact = document.getElementById('companyContact').value;
        var totalemployees = document.getElementById('totalEmployee').value;
        var paymentid = obj.razorpay_payment_id;
        var orderid = obj.razorpay_order_id;
        var signatureid = obj.razorpay_signature;
        var options = {
            "compnayname": companyname, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "compnayemail": email,
            "adminname": adminname,
            "paymentid": paymentid,
            "noofemployee": totalemployees,
            "orderid": orderid,
            "signatureid": signatureid,
            "contact": contact
        };
        optionobj = options;
    }
    document.getElementById("rzp-button1").addEventListener("click", function(e) {
        createOrder();
        let options = getoptionobj(orderobject);
        var rzp1 = new Razorpay(options);
        rzp1.open();
        rzp1.on('payment.failed', function (response){
            console.log(response);
        });
        rzp1.on('payment.success', function (response){
            console.log(response);
            getValues(response);
            console.log(optionobj);
            $.ajax({
                url: "auth.php",
                method: "POST",
                data: {
                    data: optionobj,
                    'request': "register",
                    'module': 'userRegister'},
                datatype: "html",
                success: function (response){
                    console.log(response);
                }
            });
        });
        e.preventDefault();
    });
</script>
</body>
</html>
<?php
}else{
    header("location:plan.php");
}

    ?>