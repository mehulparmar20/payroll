<?php

$product_name = $_POST["plan_name"];
$price = $_POST["price"];
$name = $_POST["company_name"];
$phone = $_POST["contact_number"];
$email = $_POST["company_email"];
$admin_id = $_POST["admin_id"];
$month = $_POST["month"];

include 'src/instamojo.php';

//$api = new Instamojo\Instamojo('d92940d57a37fdd53be2afe0be92951d', '2ce9a723e31df549bc6c397713ae2a13');
$api = new Instamojo\Instamojo('test_04ec74487fe6b9c0787143439e4', 'test_aa0195b57b9d44c936c22522a53', 'https://test.instamojo.com/api/1.1/');

try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => $product_name,
        "amount" => $price,
        "buyer_name" => $name,
        "phone" => $phone,
//        "send_email" => true,
//        "send_sms" => true,
        "email" => $email,
        'allow_repeated_payments' => false,
        "redirect_url" => "http://windsonpayroll.com/Nbp_Payroll/payment_gateway/thankyou.php",
        "webhook" => "http://windsonpayroll.com/Nbp_Payroll/payment_gateway/webhook.php"
    ));
    //print_r($response);

    $pay_ulr = $response['longurl'];

    //Redirect($response['longurl'],302); //Go to Payment page
    
    header("Location: $pay_ulr");
    exit();
} catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}
?>