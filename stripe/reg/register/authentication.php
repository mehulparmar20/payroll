<?php 
$authtoken = $_GET['token'];
?>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    /* positition: relative; */
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 150px;
    /* -webkit-box-reflect: below 1px linear-gradient(#0001, #0004); */
}

.container .loader {
    position: relative;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background-color: #424242;
    animation: animate 1s linear infinite;
}

@keyframes animate {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.container .loader::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    background: linear-gradient(to top, transparent, dodgerblue);
    background-size: 100px 180px;
    background-repeat: no-repeat;
    border-top-left-radius: 100px;
    border-bottom-left-radius: 100px;
}

.container .loader::after {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 10px;
    height: 10px;
    background-color: #00B0FF;
    border-radius: 50%;
    z-index: 10;
    box-shadow: 0 0 10px #00B0FF,
        0 0 20px #00B0FF,
        0 0 30px #00B0FF,
        0 0 40px #00B0FF,
        0 0 50px #00B0FF,
        0 0 60px #00B0FF,
        0 0 70px #00B0FF,
        0 0 80px #00B0FF,
        0 0 90px #00B0FF,
        0 0 100px #00B0FF;
}

.container .loader span {
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
    border-radius: 50%;
    background-color: #FFFFFF;
}

p {
    font-size: 40px;
}
</style>
<div class="container">
    <input type="hidden" id="token" value="<?php echo $authtoken?>">
    <div class="loader">
        <span></span>
    </div class="msg">
</div>
<center><br>
    <p>Authenticating your Account...</p>
    <center>
        </div>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
        </script>
        <script type="text/javascript" src="js/script.js">
        </script>
        <script>
        $(document).ready(function() {
            var token = $("#token").val();
            authJWT(token);
        });
        </script>