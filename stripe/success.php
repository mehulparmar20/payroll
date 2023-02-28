<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Windson Dispatch: A Complete Trucking Business Solutions.</title>
    <link rel="stylesheet" href="./style.css">

  </head>
  <style>
        body {
  background: white;
  background: linear-gradient(135deg, white 0%, #f3f6ff 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff", endColorstr="#f3f6ff", GradientType=1);
  font-family: "proxima-nova";
  padding-top: 100px;
  text-align: center;
}

.wrapper {
  -webkit-animation: wrapperAni 230ms ease-in 200ms forwards;
  animation: wrapperAni 230ms ease-in 200ms forwards;
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: inline-block;
  height: 400px;
  margin: 0 20px;
  opacity: 0;
  position: relative;
  vertical-align: top;
  width: 300px;
}

.header__wrapper {
  height: 200px;
  overflow: hidden;
  position: relative;
  width: 100%;
}

.header {
  -webkit-animation: headerAni 230ms ease-in 430ms forwards;
  animation: headerAni 230ms ease-in 430ms forwards;
  border-radius: 0;
  height: 700px;
  left: -200px;
  opacity: 0;
  position: absolute;
  top: -500px;
  width: 700px;
}

.header .sign {
  -webkit-animation: signAni 430ms ease-in 660ms forwards;
  animation: signAni 430ms ease-in 660ms forwards;
  border-radius: 50%;
  bottom: 50px;
  display: block;
  height: 100px;
  left: calc(50% - 50px);
  opacity: 0;
  position: absolute;
  width: 100px;
}

h1,
p {
  margin: 0;
}

h1 {
  color: rgba(0, 0, 0, 0.8);
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 10px;
  padding-top: 50px;
}

p {
  color: rgba(0, 0, 0, 0.7);
  padding: 0 40px;
  font-size: 18px;
  line-height: 1.4em;
}

.button1 {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 20px;
  bottom: -20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.7);
  cursor: pointer;
  font-family: inherit;
  font-size: 16px;
  font-weight: 600;
  height: 40px;
  right: calc(40% - 85px);
  outline: none;
  position: absolute;
  transition: all 170ms ease-in;
  width: 100px;
}
.button2 {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 20px;
  bottom: -20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.7);
  cursor: pointer;
  font-family: inherit;
  font-size: 16px;
  font-weight: 600;
  height: 40px;
  left: calc(41% - 85px);
  outline: none;
  position: absolute;
  transition: all 170ms ease-in;
  width: 100px;
}

.green .header {
  background-color: #bef0c8;
}

.green .sign {
  background-color: #4ec45e;
  box-shadow: 0 0 0 15px #74d181, 0 0 0 30px #9bdea4;
}

.green .sign:before,
.green .sign:after {
  background: white;
  border-radius: 2px;
  content: "";
  display: block;
  height: 40px;
  left: calc(50% - 2px);
  position: absolute;
  top: calc(50% - 20px);
  width: 5px;
}

.green .sign:before {
  left: calc(50% + 5px);
  transform: rotate(45deg);
  top: calc(50% - 20px);
}

.green .sign:after {
  height: 20px;
  left: calc(50% - 15px);
  transform: rotate(-45deg);
  top: calc(50% - 5px);
}

.green button:hover {
  border-color: #4ec45e;
}

.green button:focus {
  background-color: #bef0c8;
  border-color: #4ec45e;
}

/*
 * ANIMATIONS
*/
@-webkit-keyframes wrapperAni {
  0% {
    opacity: 0;
    transform: scale(0.95) translateY(40px);
  }

  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes wrapperAni {
  0% {
    opacity: 0;
    transform: scale(0.95) translateY(40px);
  }

  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@-webkit-keyframes headerAni {
  0% {
    border-radius: 0;
    opacity: 0;
    transform: translateY(-100px);
  }

  100% {
    border-radius: 50%;
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes headerAni {
  0% {
    border-radius: 0;
    opacity: 0;
    transform: translateY(-100px);
  }

  100% {
    border-radius: 50%;
    opacity: 1;
    transform: translateY(0);
  }
}

@-webkit-keyframes signAni {
  0% {
    opacity: 0;
    transform: scale(0.3) rotate(180deg);
  }

  60% {
    transform: scale(1.3);
  }

  80% {
    transform: scale(0.9);
  }

  100% {
    opacity: 1;
    transform: scale(1) rotate(0);
  }
}

@keyframes signAni {
  0% {
    opacity: 0;
    transform: scale(0.3) rotate(180deg);
  }

  60% {
    transform: scale(1.3);
  }

  80% {
    transform: scale(0.9);
  }

  100% {
    opacity: 1;
    transform: scale(1) rotate(0);
  }
}

/*
 * EMBED STYLING
*/
@media (max-width: 800px) {

  html,
  body {
    height: 600px;
    overflow: hidden;
    width: 800px;
  }
}
    </style>
  <body>
    <div class="wrapper green">
      <div class="header__wrapper">
        <div class="header">
          <div class="sign"><span></span></div>
        </div>
      </div>
      <h1>Success</h1>
      <p>Your payment has been completed successfully.</p>

      <button class="button1" onclick="window.location.href ='../index.php';" >Login</button>
      <button class="button2" onclick="window.location.href ='www.windsonpayroll.com';">Home</button>
  </div>
        <!-- partial -->

    </body>
</html>
