<?php
include_once '../dbconfig.php';
include_once '../stripe/model/Stripeupdate.php';
include_once 'api/index.php';
$stripe = new Stripeupdate();
$api = new Util();
if(isset($_POST['action'])){
    $action = $_POST['action'];
    if($action == 'buyemployee'){
        $quntity = (int)$_POST['quntity'];
        $planId = 'price_1IYa1ZHpxXHfgq9U6r7w1oul';
        $res = $stripe->incSubscriptionqty($planId,$quntity,$conn);
        $res = $api->incrementtotal($_SESSION['admin_id'],$quntity,$conn);
        echo json_encode($res);
    // }elseif($action == 'buyemployee'){
    //     $quntity = $_POST['qunitity'];
    //     $planId = 'price_1IYa1ZHpxXHfgq9U6r7w1oul';
    //     $res = $stripe->incSubscriptionqty($planId,$quntity,$conn);
    }
}